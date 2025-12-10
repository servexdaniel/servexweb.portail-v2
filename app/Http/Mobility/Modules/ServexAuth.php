<?php
namespace App\Http\Mobility\Modules;
use App\Http\Mobility\Interfaces\IServexAuth;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Commands\CoValidateWebLogin;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Exception;
class ServexAuth implements IServexAuth
{
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

                dd($user_info);
                /*
                if ($user_info['LoginSuccess'] == "SUCCES") {
                    $contact = Contact::find(Auth::guard('contact')->user()->id);

                    $contact->password    = \Hash::make($this->password);
                    $contact->CcUnique     = $user_info['CcUnique'];
                    $contact->CuNumber    = $this->ccCustomerNumber;
                    $contact->CuName      = $user_info['CuName'];

                    $contact->CcName      = $user_info['CcName'];
                    $contact->CcIsManager = filter_var($user_info['CcIsManager'], FILTER_VALIDATE_BOOLEAN);
                    $contact->CcPortailAdmin = filter_var($user_info['CcPortailAdmin'], FILTER_VALIDATE_BOOLEAN);
                    $contact->CcPhoneNumber      = $user_info['CcPhoneNumber'];
                    $contact->CcEmail      = isset($user_info['CcEmail']) ? $user_info['CcEmail'] : "";

                    $contact->CcPhoneExt   = isset($user_info['CcPhoneExt']) ? $user_info['CcPhoneExt'] : "";
                    $contact->CcCellNumber = isset($user_info['CcCellNumber']) ? $user_info['CcCellNumber'] : "";

                    $contact->LoginSuccess = $user_info['LoginSuccess'];
                    $contact->ReasonLogin = $user_info['ReasonLogin'];

                    $contact->save();

                    $client = getCurrentTenant();

                    //Mettre à jour la société en lien avec la session actuelle
                    Company::where('contact_id', $contact->id)->update(['isActiveSession' => 0]);
                    Company::where('contact_id', $contact->id)
                        ->where('customer_id', $client->id)
                        ->where('CcCustomerNumber', $this->ccCustomerNumber)->update(['isActiveSession' => 1]);


                    //Mettre à jour les données du consumer
                    event(new UpdateConsumerEvent($client, $contact, $this->ccCustomerNumber));

                    return true;
                }
                return false;
                */
            }
            return false;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
