<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;

class OnFailedJob
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JobFailed $event): void
    {
        $job = $event->job;
        $jobID = $job->getJobId();
        $payload = $job->payload();
        info("JobFailed JobID: $jobID");
        info($payload['data']['command']);
    }
}
