<?php

namespace App\Livewire\Settings\Calls\Form\Visualisation;

use Livewire\Component;
use App\Models\CallColumn;
use Illuminate\Support\Facades\DB;
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
        //$this->selectAllBtnStatus();
        return $this->columns;
    }
    

    public function mount()
    {
        $this->getColumns();
        //$this->enableAllMandatoryColumns();
    }

    public function render()
    {
        return view('livewire.settings.calls.form.visualisation.columns');
    }
}
