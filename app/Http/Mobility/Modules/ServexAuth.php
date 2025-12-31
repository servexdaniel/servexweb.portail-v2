<?php
namespace App\Http\Mobility\Modules;
use App\Http\Mobility\Interfaces\IServexAuth;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Commands\CoValidateWebLogin;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Servex\Traits\UsesDomainTrait;

class ServexAuth implements IServexAuth
{
    use UsesDomainTrait;
    private ServexMobilityClient $servexMobilityClient;
    private $separator;
    private string $ccCustomerNumber;
    private string $username;
    private string $password;
    private String $messageId;

    public function __construct(string $ccCustomerNumber, string $username, string $password)
    {
        $this->servexMobilityClient = new ServexMobilityClient();
        $this->separator  = mb_chr(254, 'UTF-8');
        $this->ccCustomerNumber = $ccCustomerNumber;
        $this->username = $username;
        $this->password = $password;
        $this->messageId = $this->servexMobilityClient->getMessageId();
    }



    public function authenticate()
    {
        try {
            $this->ensureConnection();
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new CoValidateWebLogin())->getParams($this->messageId, [
                'ccCustomerNumber' => $this->ccCustomerNumber,
                'username' => $this->username,
                'password' => $this->password
            ]);

            $this->servexMobilityClient->send($commandHdr->message);
            $this->servexMobilityClient->commitTransaction();

            $response = $this->decodeResponse($this->servexMobilityClient->read());
            if (is_null($response) || empty($response['data'])) {
                return null;
            }

            $user_info = $response['data'];
            // Échec de login
            if (!$user_info || ($user_info['LoginSuccess'] !== "SUCCES" && !isset($user_info['ListeClient']) && !$user_info['CcIsManager'])) {
                return null;
            }

            // Cas 1 : L'utilisateur a plusieurs compagnies → on lui demande de choisir
            if (!empty($user_info['ListeClient'])) {
                $customers = $this->getCustomers($user_info['ListeClient']);

                // On stocke temporairement en session pour le choix futur
                session()->put('servex_customers', $customers); //ou \Session::put('servex_customers', $customers);
                session()->put('servex_user_info', $user_info); // au cas où besoin plus tard

                session()->put('servex_temp_username', $this->username);
                session()->put('servex_temp_password', $this->password); // attention : mot de passe en clair !

                $token = \Str::random(60);
                session()->put('servex_auth_token', $token);
                session()->put('servex_pending_auth', [
                    'username' => $this->username,
                    'password' => $this->password,
                    'user_info' => $user_info,
                    'customers' => $customers,
                ]);

                // Retourne la liste pour affichage (modal ou page de sélection)
                return [
                    'type'       => 'choose_company',
                    'customers'  => $customers,
                    'message'    => 'Veuillez sélectionner votre compagnie',
                ];
            }

            // Cas 2 : Une seule compagnie → création ou récupération du Contact + connexion automatique
            $contact = $this->createContactFromUserInfo($user_info);

            if (!$contact || !$contact->save()) {
                return null;
            }

            return [
                'type'    => 'single_company',
                'contact' => $contact,
                'message' => 'Connexion réussie',
            ];

        } catch (\Exception $e) {
            Log::error('Servex authentication failed', [
                'username' => $this->username,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function ensureConnection()
    {
        if (!$this->servexMobilityClient->connect()) {
            throw new Exception("GetContact : Connexion impossible");
        }
    }

    private function decodeResponse($response)
    {
        return json_decode($response, true);
    }

    private function getCustomers(string $fields): array
    {
        $parts = explode($this->separator, $fields);

        // Supprimer le dernier élément vide s'il existe (causé par le þ final)
        if (end($parts) === '' || end($parts) === false) {
            array_pop($parts);
        }

        // Vérifier qu'on a un nombre pair d'éléments (sinon il y a un problème de données)
        if (count($parts) % 2 !== 0) {
            // Optionnel : logger une alerte, ou ignorer le dernier élément incomplet
            Log::warning('Nombre impair de champs dans getCustomers(), dernier ignoré.', ['fields' => $fields]);
            // On enlève le dernier élément orphelin
            array_pop($parts);
        }

        // Regrouper par paires de 2
        $chunks = array_chunk($parts, 2);

        // Construire le tableau propre
        $customers = array_map(function ($chunk) {
            return [
                'CcCustomerNumber' => trim($chunk[0]),
                'CcName'           => trim($chunk[1]),
            ];
        }, $chunks);

        // Trier par nom (insensible à la casse)
        usort($customers, function ($a, $b) {
            return strcasecmp($a['CcName'], $b['CcName']);
        });
        return $customers;
    }

    private function createContactFromUserInfo(array $user_info)
    {
        $client = $this->getCurrentTenant();
        $contact = new Contact();

        $ccIsManager = filter_var($user_info['CcIsManager'], FILTER_VALIDATE_BOOLEAN);

        $timezone = config('app.timezone');
        $now = now();
        $now->setTimezone(new \DateTimeZone($timezone));

        $contact->username    = $this->username;
        $contact->customer_id = $client->id;
        $contact->password    = \Hash::make($this->password);
        $contact->CuNumber    = $this->ccCustomerNumber;
        $contact->connected_at = $now->format('Y-m-d H:i:s');
        $contact->CuName = $ccIsManager ? $client->name : utf8_encode(html_entity_decode(utf8_decode($user_info['CuName'])));
        $contact->CcName      = $this->username;
        $contact->CcIsManager = $ccIsManager;
        $contact->CcPortailAdmin = isset($user_info['CcPortailAdmin']) ? filter_var($user_info['CcPortailAdmin'], FILTER_VALIDATE_BOOLEAN) : false;
        $contact->CcPhoneNumber      = isset($user_info['CcPhoneNumber']) ? html_entity_decode(utf8_decode($user_info['CcPhoneNumber'])) : null;
        $contact->CcEmail      = isset($user_info['CcEmail']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcEmail']))) : null;
        $contact->email      = isset($user_info['CcEmail']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcEmail']))) : null;
        $contact->CcPhoneExt   = isset($user_info['CcPhoneExt']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcPhoneExt']))) : null;
        $contact->CcCellNumber = isset($user_info['CcCellNumber']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcCellNumber']))) : null;

        $contact->LoginSuccess = html_entity_decode(utf8_decode($user_info['LoginSuccess']));
        $contact->ReasonLogin = html_entity_decode(utf8_decode($user_info['ReasonLogin']));

        return $contact;
    }
}
