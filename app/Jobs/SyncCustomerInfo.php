<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncCustomerInfo implements ShouldQueue
{
    use Queueable;

    public $cunumber;
    public $contactId;

    /**
     * Create a new job instance.
     */
    public function __construct($cunumber, $contactId)
    {
        $this->cunumber = $cunumber;
        $this->contactId = $contactId;
        Log::info("----> Create Job SyncCustomerInfo ".$this->cunumber." for contact ID ".$this->contactId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new ServexSynchro())->getCustomerInfo($this->cunumber, $this->contactId);
    }
}
