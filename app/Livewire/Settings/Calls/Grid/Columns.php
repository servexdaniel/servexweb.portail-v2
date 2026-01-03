<?php

namespace App\Livewire\Settings\Calls\Grid;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Servex\Traits\UsesDomainTrait;

class Columns extends Component
{
    use UsesDomainTrait;
    /*
    public function render()
    {
        return view('livewire.settings.calls.grid.columns');
    }
    */

    public $columns;
    public $isAllMandatoryColumnsActive = false;

    public function getColumns()
    {

        $client = $this->getCurrentTenant();

        $this->isAllMandatoryColumnsActive = DB::table("servex_call_columns")
            ->where('servex_call_columns.ismandatory', '=', 1)
            ->whereNotIn('servex_call_columns.id', function($query) use ($client) {
                $query->select('servex_customer_call_columns.column_id')
                    ->from('servex_customer_call_columns')
                    ->where('servex_customer_call_columns.customer_id', $client->id);
            })
            ->count() === 0;

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
