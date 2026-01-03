<?php

namespace App\Livewire\Settings\Calls\Grid;

use Livewire\Component;
use App\Models\CallColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Servex\Traits\UsesDomainTrait;

class Columns extends Component
{
    use UsesDomainTrait;

    public $columns;
    public $isAllMandatoryColumnsActive = false;

    public function getColumns()
    {
        $client = $this->getCurrentTenant();

        $this->isAllMandatoryColumnsActive = CallColumn::query()
            ->where('ismandatory', 1)
            ->where('display_in_grid', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_columns')
                    ->where('customer_id', $client->id);
            })
            ->doesntExist();


        $this->columns = DB::table("servex_call_columns")
            ->select("servex_call_columns.*",
                        DB::raw("(  SELECT CASE WHEN servex_customer_call_columns.column_id = servex_call_columns.id THEN TRUE ELSE FALSE END
                                    FROM servex_customer_call_columns
                                    WHERE servex_customer_call_columns.customer_id = ".$client->id ."
                                    AND servex_customer_call_columns.column_id = servex_call_columns.id) AS visible
                        "),
                    )
            ->where('servex_call_columns.display_in_grid', '=', true)
            ->where('servex_call_columns.ismandatory', '<>', 1)
            ->orderBy('servex_call_columns.display_order', 'ASC')
            ->get()->toArray();
        return $this->columns;
    }

    public function enableAllMandatoryColumns()
    {
        $client = $this->getCurrentTenant();

        // Récupérer les colonnes obligatoires qui ne sont PAS encore activées pour ce client
        $missingMandatoryColumns = CallColumn::query()
            ->where('ismandatory', 1)
            ->where('display_in_grid', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_columns')
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
        DB::table('servex_customer_call_columns')->insert($dataToInsert);

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
        $missingDefaultColumns = CallColumn::query()
            ->where('isdefault', 1)
            ->where('display_in_grid', 1)
            ->whereNotIn('id', function ($query) use ($client) {
                $query->select('column_id')
                    ->from('servex_customer_call_columns')
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
        DB::table('servex_customer_call_columns')->insert($dataToInsert);

        // Optionnel : log ou message de confirmation
        Log::info("Colonnes par défaut activées pour le client {$client->id}", [
            'columns_added' => $missingDefaultColumns->pluck('id')->toArray()
        ]);
        $this->getColumns();
    }

    public function toggleColumn($columnId)
    {
        $client = $this->getCurrentTenant();

        $exists = DB::table("servex_customer_call_columns")
            ->where('customer_id', $client->id)
            ->where('column_id', $columnId)
            ->exists();

        if ($exists) {
            DB::table("servex_customer_call_columns")
                ->where('customer_id', $client->id)
                ->where('column_id', $columnId)
                ->delete();
        } else {
            DB::table("servex_customer_call_columns")
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
    }

    public function render()
    {
        return view('livewire.settings.calls.grid.columns');
    }
}
