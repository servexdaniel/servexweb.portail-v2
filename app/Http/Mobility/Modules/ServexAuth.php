<?php
namespace App\Http\Mobility\Modules;
use App\Http\Mobility\Interfaces\IServexAuth;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Commands\CoValidateWebLogin;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Exception;
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
            //Activer la connexion
            if (!$this->servexMobilityClient->connect()) throw new Exception("Authenticate : Connexion impossible");
            //Début de la transaction via le rabbitmq
            $this->servexMobilityClient->beginTransaction();

            $commandHdr = (new CoValidateWebLogin())->getParams($this->messageId, [
                'ccCustomerNumber' => $this->ccCustomerNumber,
                'username' => $this->username,
                'password' => $this->password
            ]);

            //Envoyer le message
            $this->servexMobilityClient->send($commandHdr->message);

            $this->servexMobilityClient->commitTransaction();

            $response = $this->servexMobilityClient->read();
            $response = json_decode($response, true); // decode
            if (!is_null($response)) {
                $user_info = $response['data'];

                $criteria = [
                    'ccCustomerNumber' => $this->ccCustomerNumber,
                    'username' => $this->username,
                    'password' => $this->password
                ];

                $client = $this->getCurrentTenant();
                $contact = new Contact();

                if ($user_info['LoginSuccess'] == "SUCCES") {

                    $CcIsManager = filter_var($user_info['CcIsManager'], FILTER_VALIDATE_BOOLEAN);

                    //Sauvegarder la session dans la table Contacts
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
                    $contact->CcIsManager = filter_var($user_info['CcIsManager'], FILTER_VALIDATE_BOOLEAN);
                    $contact->CcPortailAdmin = isset($user_info['CcPortailAdmin']) ? filter_var($user_info['CcPortailAdmin'], FILTER_VALIDATE_BOOLEAN) : false;
                    $contact->CcPhoneNumber      = isset($user_info['CcPhoneNumber']) ? html_entity_decode(utf8_decode($user_info['CcPhoneNumber'])) : '';
                    $contact->CcEmail      = isset($user_info['CcEmail']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcEmail']))) : "";

                    $contact->CcPhoneExt   = isset($user_info['CcPhoneExt']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcPhoneExt']))) : "";
                    $contact->CcCellNumber = isset($user_info['CcCellNumber']) ? utf8_encode(html_entity_decode(utf8_decode($user_info['CcCellNumber']))) : "";

                    $contact->LoginSuccess = html_entity_decode(utf8_decode($user_info['LoginSuccess']));
                    $contact->ReasonLogin = html_entity_decode(utf8_decode($user_info['ReasonLogin']));
                    return $contact->save();
                }
                return false;
            }
            return false;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
