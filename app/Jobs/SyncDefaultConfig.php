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
        Log::info("----> Job executed syncDefaultConfig::CoUseNewDataX");
        (new ServexSynchro())->syncWebConfig();
        Log::info("----> Job executed syncDefaultConfig::syncWebConfig");
        (new ServexSynchro())->syncCodes();
        Log::info("----> Job executed syncDefaultConfig::syncCodes ");
        (new ServexSynchro())->syncTravels();
        Log::info("----> Job executed syncDefaultConfig::syncTravels ");
        (new ServexSynchro())->syncLabours();
        Log::info("----> Job executed syncDefaultConfig::syncLabours ");
        (new ServexSynchro())->syncPriorities();
        Log::info("----> Job executed syncDefaultConfig::syncPriorities");
        (new ServexSynchro())->syncTechnicians();
        Log::info("----> Job executed syncDefaultConfig::syncTechnicians ");
        (new ServexSynchro())->syncDispatchers();
        Log::info("----> Job executed syncDefaultConfig::syncDispatchers ");
        (new ServexSynchro())->syncCompanyExtraInfo();
        Log::info("----> Job executed syncDefaultConfig::syncCompanyExtraInfo ");
        (new ServexSynchro())->syncCpaWeb();
        Log::info("----> Job executed syncDefaultConfig::syncCpaWeb ");
        (new ServexSynchro())->syncDataX();
        Log::info("----> Job executed SyncDataX  ");
    }
}
