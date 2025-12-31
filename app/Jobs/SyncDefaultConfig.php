<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Mobility\Modules\ServexSynchro;

class SyncDefaultConfig implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        Log::info("----> Create Job syncDefaultConfig");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new ServexSynchro())->CoUseNewDataX();
        Log::info("----> Handle Job syncDefaultConfig::CoUseNewDataX");
        (new ServexSynchro())->syncWebConfig();
        Log::info("----> Job executed syncDefaultConfig::syncWebConfig");
        /*
        (new ServexSynchro())->syncCodes();
        Log::info("----> Job executed syncCodes ");
        (new ServexSynchro())->syncTravels();
        Log::info("----> Job executed syncTravels ");
        (new ServexSynchro())->syncLabours();
        Log::info("----> Job executed syncLabors ");
        (new ServexSynchro())->syncPriorities();
        Log::info("----> Job executed syncPriorities");
        (new ServexSynchro())->syncTechnicians();
        Log::info("----> Job executed syncTechnicians ");
        (new ServexSynchro())->syncDispatchers();
        Log::info("----> Job executed syncDispatchers ");
        (new ServexSynchro())->syncCompanyExtraInfo();
        Log::info("----> Job executed syncCompanyExtraInfo ");
        $countCPAWeb = count((new ServexSynchro())->syncCpaWeb());
        if ($countCPAWeb == 0) {
            (new ServexSynchro())->syncCpaWeb();
        }
        Log::info("----> Job executed syncCpaWeb ");
        $this->syncDataX();
        Log::info("----> Job executed SyncDataX  ");
        */
    }
}
