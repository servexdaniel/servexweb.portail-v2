<?php

namespace App\Livewire\Calls;

use Livewire\Component;
use Illuminate\Support\Arr;
use App\Servex\Traits\UsesDomainTrait;
use App\Http\Mobility\Modules\ServexCall;

class GetCalls extends Component
{
    use UsesDomainTrait;

    public $isCallComplete = false;

    function GetCallsByStatus()
    {
        if (!$this->isCallComplete) {
            $visibleFields = $this->getCallGridVisibleFields();
        } else {
            $visibleFields = $this->getCallCompleteGridVisibleFields();
        }

        $colnames = Arr::pluck($visibleFields, 'key');
        $fields = implode("þ", $colnames);
        $fields =  $fields . "þ";

        $calls = (new ServexCall())->GetCalls($fields, $this->isCallComplete, $visibleFields);
        return $calls;
    }

    private function getCallGridVisibleFields()
    {
        $client = $this->getCurrentTenant();
        $visibleColumns = $client->callColumns->sortBy('display_order')->sortBy('id');

        //Exclure CaInvoiceNumber pour les appels en cours
        $filtered = $visibleColumns->filter(function ($value, $key) {
            if (($value->column != "CaInvoiceNumber") && ($value->column != "CaCompleteDate") && ($value->column != "CaCompleteUserNumber")) {
                return $value->column;
            }
        });

        $visibleColumns = $filtered->all();

        $newArray = array();
        foreach ($visibleColumns as $visibleCol) {
            if ($visibleCol->display_in_grid == 1 || $visibleCol->ismandatory == 1) {
                array_push(
                    $newArray,
                    array(
                        'id'                 => $visibleCol->id,
                        'key'                => $visibleCol->column,
                        //'title'              => getCustomLabel($visibleCol->column),
                        'title'              => $visibleCol->column,
                        'isdefault'          => $visibleCol->isdefault,
                        'ismandatory'        => $visibleCol->ismandatory,
                        'display_in_grid'    => $visibleCol->display_in_grid
                    )
                );
            }
        }
        return $newArray;
    }

    public function render()
    {
        dd($this->GetCallsByStatus());
        return view('livewire.calls.get-calls');
    }
}
