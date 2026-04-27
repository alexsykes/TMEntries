<?php

namespace App\Listeners;

use App\Events\TrialBackupCompleted;
use App\Mail\TrialBackupCompletedMail;
use App\Models\Trial;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class OnTrialBackupCompleted
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
    public function handle(TrialBackupCompleted $event): void
    {
        info('OnTrialBackupCompleted - trialID: '.$event->id);
        $trialID = $event->id;
        $trial = Trial::where('id', $trialID)
            ->select('contactName', 'email')
            ->first();

        $name = $trial->contactName;
        $email = $trial->email;

        $name = 'Test Address';
        $email = 'alexjeddah@icloud.com';

        Mail::to(new Address($email, $name))->send(new TrialBackupCompletedMail($trialID));
    }
}
