<?php

namespace App\Livewire\Settings\Calls\Form\Creation;

use Livewire\Component;
use App\Models\CallDetailColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Servex\Traits\UsesDomainTrait;

class Columns extends Component
{
    use UsesDomainTrait;

    public $columns;
    public $selectall = false;
    public $isAllMandatoryColumnsActive = false;

    protected $listeners = [
        'alltrigger' => 'handleAllTrigger',
    ];

    public function getColumns()
    {
        $client = $this->getCurrentTenant();

        $this->isAllMandatoryColumnsActive = CallDetailColumn::query()
            ->where('ismandatory', 1)
            ->where('display_in_creation', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_creation_columns')
                    ->where('customer_id', $client->id);
            })
            ->doesntExist();


        $this->columns = DB::table("servex_call_detail_columns")
            ->select("servex_call_detail_columns.*",
                        DB::raw("(  SELECT CASE WHEN  servex_customer_call_creation_columns.column_id = servex_call_detail_columns.id THEN TRUE ELSE FALSE END
                                    FROM  servex_customer_call_creation_columns
                                    WHERE  servex_customer_call_creation_columns.customer_id = ".$client->id ."
                                    AND servex_call_detail_columns.display_in_creation = 1
                                    AND  servex_customer_call_creation_columns.column_id = servex_call_detail_columns.id) AS visible
                        "),
                    )
            ->where('servex_call_detail_columns.ismandatory', '<>', 1)
            ->where('servex_call_detail_columns.display_in_creation', 1)
            ->orderBy('servex_call_detail_columns.display_order', 'ASC')
            ->get()->toArray();
        $this->selectAllBtnStatus();
        return $this->columns;
    }
    
    public function handleAllTrigger()
    {
        $value = $this->selectall;
        $client = $this->getCurrentTenant();

        if ($value) {
            // Récupérer les colonnes obligatoires qui ne sont PAS encore activées pour ce client
            $columns = CallDetailColumn::query()
                ->where('display_in_creation', 1)
                ->whereNotIn('id', function ($query) use ($client) {
                    $query->select('column_id')
                        ->from('servex_customer_call_creation_columns')
                        ->where('customer_id', $client->id);
                })
                ->get();

            // Préparer les données à insérer dans la table pivot
            $dataToInsert = $columns->map(function ($column) use ($client) {
                return [
                    'customer_id'     => $client->id,
                    'column_id'       => $column->id,
                ];
            })->toArray();

            // Insertion en masse (une seule requête SQL → très performant)
            DB::table('servex_customer_call_creation_columns')->insert($dataToInsert);
        } else {
            // Désactiver toutes les colonnes non obligatoires pour ce client
            $client = $this->getCurrentTenant();
            DB::table('servex_customer_call_creation_columns')
                ->where('customer_id', $client->id)
                ->whereIn('column_id', function ($query) {
                    $query->select('id')
                        ->from('servex_call_detail_columns')
                        ->where('display_in_creation', 1)
                        ->where('ismandatory', 0);
                })
                ->delete();
        }
        $this->getColumns();
    }
    
    public function selectAllBtnStatus()
    {
        $visibleActiveColumns = collect($this->columns)->filter(function ($item) {
            return $item->visible == true;
        })->count();
        $this->selectall = $visibleActiveColumns == count($this->columns);
    }

    public function enableAllMandatoryColumns()
    {
        $client = $this->getCurrentTenant();

        // Récupérer les colonnes obligatoires qui ne sont PAS encore activées pour ce client
        $missingMandatoryColumns = CallDetailColumn::query()
            ->where('ismandatory', 1)
            ->where('display_in_creation', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_creation_columns')
                    ->where('customer_id', $client->id);
            })
            ->get();

        // Si aucune colonne ne manque → rien à faire
        if ($missingMandatoryColumns->isEmpty()) {
            return;
        }

        // Préparer les données à insérer dans la table pivot
        $dataToInsert = $missingMandatoryColumns->map(function ($column) use ($client) {
            return [
                'customer_id'     => $client->id,
                'column_id'       => $column->id,
            ];
        })->toArray();

        // Insertion en masse (une seule requête SQL → très performant)
        DB::table('servex_customer_call_creation_columns')->insert($dataToInsert);

        // Optionnel : log ou message de confirmation
        Log::info("Colonnes obligatoires activées pour le client {$client->id}", [
            'columns_added' => $missingMandatoryColumns->pluck('id')->toArray()
        ]);
        $this->enableAllDefaultColumns();
        $this->getColumns();
    }

    public function enableAllDefaultColumns()
    {
        $client = $this->getCurrentTenant();

        // Récupérer les colonnes par défaut qui ne sont PAS encore activées pour ce client
        $missingDefaultColumns = CallDetailColumn::query()
            ->where('isdefault', 1)
            ->where('display_in_creation', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_creation_columns')
                    ->where('customer_id', $client->id);
            })
            ->get();

        // Si aucune colonne ne manque → rien à faire
        if ($missingDefaultColumns->isEmpty()) {
            return;
        }

        // Préparer les données à insérer dans la table pivot
        $dataToInsert = $missingDefaultColumns->map(function ($column) use ($client) {
            return [
                'customer_id'     => $client->id,
                'column_id'       => $column->id,
            ];
        })->toArray();

        // Insertion en masse (une seule requête SQL → très performant)
        DB::table('servex_customer_call_creation_columns')->insert($dataToInsert);

        // Optionnel : log ou message de confirmation
        Log::info("Colonnes par défaut activées pour le client {$client->id}", [
            'columns_added' => $missingDefaultColumns->pluck('id')->toArray()
        ]);
        $this->getColumns();
    }

    public function toggleColumn($columnId)
    {
        $client = $this->getCurrentTenant();

        $exists = DB::table("servex_customer_call_creation_columns")
            ->where('customer_id', $client->id)
            ->where('column_id', $columnId)
            ->exists();

        if ($exists) {
            DB::table("servex_customer_call_creation_columns")
                ->where('customer_id', $client->id)
                ->where('column_id', $columnId)
                ->delete();
        } else {
            DB::table("servex_customer_call_creation_columns")
                ->insert([
                    'customer_id' => $client->id,
                    'column_id' => $columnId,
                ]);
        }

        $this->getColumns();
    }

    public function mount()
    {
        $this->getColumns();
        $this->enableAllMandatoryColumns();
    }

    public function render()
    {
        return view('livewire.settings.calls.form.creation.columns');
    }
}
