<?php

use App\Models\ClubMember;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
    info('Running: inspire');
})->purpose('Display an inspiring quote');


Artisan::command('addToken', function () {
    $clubMembers = ClubMember::all();
    foreach ($clubMembers as $clubMember) {
        $token = bin2hex(random_bytes(16));
        $clubMember->token = $token;
        $clubMember->save();
    }
});
// Check for unconfirmed entries
// Schedule::command(CheckEntries::class)->dailyAt('01:00');
// Schedule::command( CheckEntries::class)->everyMinute();
