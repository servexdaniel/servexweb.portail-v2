<?php
namespace App\Http\Mobility\Modules;
use DateTime;
use Exception;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Servex\Traits\UsesColorsTrait;
use App\Servex\Traits\UsesDomainTrait;
use App\Http\Mobility\Commands\SrCalls;
use App\Http\Mobility\ServexMobilityClient;
use App\Http\Mobility\Interfaces\IServexAuth;

class ServexCall
{
    use UsesDomainTrait, UsesColorsTrait;
    private ServexMobilityClient $servexMobilityClient;
    private $separator;
    private string $canumber;
    private bool $isCallComplete;
    private String $messageId;
    private array $call = [];

    public function __construct(?string $canumber = null, ?bool $isCallComplete = null)
    {
        $this->servexMobilityClient = new ServexMobilityClient();
        $this->separator  = mb_chr(254, 'UTF-8');
        $this->canumber = $canumber ?? '';
        $this->isCallComplete = $isCallComplete ?? false;
        $this->messageId = $this->servexMobilityClient->getMessageId();
    }

    public function GetCalls($fields, $isCallComplete, $visibleFields)
    {
        //Activer la connexion
        if (!$this->servexMobilityClient->connect()) throw new Exception("GetCalls : Connexion impossible");
        //Début de la transaction via le rabbitmq
        $this->servexMobilityClient->beginTransaction();

        $commandHdr = (new SrCalls())->getParams($this->messageId, [
            'isCallComplete' => $isCallComplete,
            'fields' => $fields,
        ]);

        //Envoyer le message
        $this->servexMobilityClient->send($commandHdr->message);

        $this->servexMobilityClient->commitTransaction();

        $response = $this->servexMobilityClient->read();

        $keys           = explode($this->separator, $commandHdr->fields);
        array_pop($keys);
        $values           = explode($this->separator, $response);
        array_pop($values);


        $countFields            = sizeof($keys);
        $body_arr  = explode($this->separator, $response);
        array_pop($body_arr);


        $dataset          = [];
        if ($countFields > 0) {
            $dataset          = array_chunk($body_arr, $countFields, false);
        }

        $dates = ['CaCallDate', 'CaCompleteDate'];
        $colors = ['CPColor'];

        //Convertir en un taleau de clé/valeur
        $columns = (explode('þ', $fields));
        array_pop($columns);
        $calls = array();
        foreach ($dataset as $call) {
            $temp = null;
            foreach ($call as $index => $c) {
                //Gestion des dates
                if (in_array($columns[$index], $dates)) {
                    $temp[$columns[$index]] = $this->ConvertColumnDate($c);
                } else {
                    if ($columns[$index] == 'CaContractNumber') {
                        if ($c == 0) {
                            $temp[$columns[$index]] = '';
                        } else {
                            $temp[$columns[$index]] = $c;
                        }
                    } else {
                        $temp[$columns[$index]] = $c;
                    }
                }

                //Gestion des couleurs ( Ex. couleur du CPA )
                if (in_array($columns[$index], $colors)) {
                    $temp[$columns[$index]] = $this->decodeColor($c);
                }
            }


            /* $temp2 = array($temp);
            $temp2 = $this->ReplaceCaCodeByDescr($temp2);
            $temp2 = $this->ReplaceTravelByDescr($temp2);
            $temp2 = $this->ReplaceLabourByDescr($temp2);
            $temp2 = $this->ReplaceTechNumberByDescr($temp2);
            $temp2 = $this->ReplaceDispatcherNumberByDescr($temp2);
            $temp = $temp2[0]; */


            array_push($calls, $temp);
        }

        //Si le CPA de l'appel est activé pour le portail, on affiche cet appel
        $filteredCalls = array();
        $list_cpa = collect($this->getCpaList())->pluck('CpTitle');
        foreach ($calls as $call) {
            $exists = in_array($call["CpTitle"], $list_cpa->toArray());
            if ($exists) {
                array_push($filteredCalls, $call);
            }
        }

        $list_cpa = $this->getCpaList();

        $data = [
            'columns'      => $visibleFields,
            'calls'        => $filteredCalls,
            'total'        => count($filteredCalls),
            'list_cpa'     => $list_cpa
        ];

        return $data;
    }

    private function validateDate($date, $format = 'Ymd')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function convertColumnDate(?string $value): string
    {
        $value = $value == "19800101" ? "" : $value;
        $value = $value == "01/01/1980" ? "" : $value;
        $timezone = config('app.timezone');

        if (empty($value)) {
            return '';
        }

        try {
            // Déterminer si la valeur contient une heure en testant les deux formats
            $format = strpos($value, ':') === false ? 'd/m/Y' : 'd/m/Y H:i:s';

            if ($this->validateDate($value, $format)) {
                $date = DateTime::createFromFormat($format, $value);

                if ($date === false || $date->getTimestamp() <= 315550800) { // 1970-01-01
                    return '';
                }

                // Si le format est une date sans heure, définir l'heure à 00:00:00
                if ($format === 'd/m/Y') {
                    $date->setTime(0, 0, 0);
                }

                $date->setTimezone(new DateTimeZone($timezone));
                return $date->format('Y-m-d H:i:s');
            } else {
                return '';
            }
        } catch (Exception) {
            return '';
        }
    }

    private function getCpaList()
    {
        $client = $this->getCurrentTenant();
        $cpas = DB::table('servex_cpa')->where(['customer_id' => $client->id])->orderBy('CpTitle')->get('CpTitle');

        $CPA_SUBMISSION = $client->getSetting('CPA_SUBMISSION');
        $CPA_SUBMISSION_ACCEPTED = $client->getSetting('CPA_SUBMISSION_ACCEPTED');
        $CPA_SUBMISSION_REJECTED = $client->getSetting('CPA_SUBMISSION_REJECTED');
        if ($CPA_SUBMISSION != -1 && $CPA_SUBMISSION_ACCEPTED != -1 && $CPA_SUBMISSION_REJECTED != -1) {
            $cpas = DB::table('servex_cpa')->where(['customer_id' => $client->id])
                ->whereNotIn('CpNumber', [$CPA_SUBMISSION, $CPA_SUBMISSION_ACCEPTED, $CPA_SUBMISSION_REJECTED])->orderBy('CpTitle')->get('CpTitle');
        }
        return $cpas->toArray();
    }
}
