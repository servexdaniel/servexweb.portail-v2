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
            if (is_null($response)) {
                return null;
            }

            $user_info = $response['data'] ?? null;
            if (!$user_info || ($user_info['LoginSuccess'] !== "SUCCES" && !isset($user_info['ListeClient']))) {
                return null;
            }

            if (isset($user_info) && isset($user_info['ListeClient'])) {
                $listeClient = $user_info['ListeClient'];
                $customers = $this->getCustomers($listeClient);
                dd($response, $customers);
            }
            else  {
                $contact = $this->createContactFromUserInfo($user_info);
                dd($contact);
                return $contact && $contact->save() ? $contact : null;

            }

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
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

        $CcIsManager = filter_var($user_info['CcIsManager'], FILTER_VALIDATE_BOOLEAN);

        $timezone = config('app.timezone');
        $now = now();
        $now->setTimezone(new \DateTimeZone($timezone));

        $contact->username    = $this->username;
        $contact->customer_id = $client->id;
        $contact->password    = \Hash::make($this->password);
        $contact->CuNumber    = $this->ccCustomerNumber;
        $contact->connected_at = $now->format('Y-m-d H:i:s');

        $contact->CuName      = $CcIsManager ? utf8_decode(utf8_encode($user_info['CuName'])) : $client->name;
        $contact->CcName      = $this->username;
        $contact->CcIsManager = $CcIsManager;
        $contact->CcPortailAdmin = isset($user_info['CcPortailAdmin']) ? filter_var($user_info['CcPortailAdmin'], FILTER_VALIDATE_BOOLEAN) : false;
        $contact->CcPhoneNumber      = isset($user_info['CcPhoneNumber']) ? html_entity_decode(utf8_decode($user_info['CcPhoneNumber'])) : '';
        $contact->CcEmail      = isset($user_info['CcEmail']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcEmail']))) : "";
        $contact->email      = isset($user_info['CcEmail']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcEmail']))) : "";

        $contact->CcPhoneExt   = isset($user_info['CcPhoneExt']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcPhoneExt']))) : "";
        $contact->CcCellNumber = isset($user_info['CcCellNumber']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcCellNumber']))) : "";

        $contact->LoginSuccess = html_entity_decode(utf8_decode($user_info['LoginSuccess']));
        $contact->ReasonLogin = html_entity_decode(utf8_decode($user_info['ReasonLogin']));

        return $contact;
    }
}
