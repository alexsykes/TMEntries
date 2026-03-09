<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobProcessed;


class LogSuccessfulJob
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
    public function handle(JobProcessed $event): void
    {
        $job = $event->job;
        $jobID = $job->getJobId();
        info("Job id: $jobID sent successfully");

//        $payload = json_decode( $event->job->getRawBody() );
//        $data = unserialize( $payload->data->command );
//        info($data);

//        $command = $job->payload()['data']['command'];
//
//
//        info($command);
//        $ser = unserialize($command);
////$splod = sizeof($ser);
//        var_dump($ser);
    }
}
