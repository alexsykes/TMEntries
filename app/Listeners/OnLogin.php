<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class OnLogin
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
    public function handle(Login $event): void
    {
        //
        $user = $event->user;
        Log::info($user->name.' with ID ('.$user->id.') successfully logged in.');
        //        Log::info("Club admin ".$user->isClubUser);
    }
}
