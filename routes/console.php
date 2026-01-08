<?php

use App\Console\Commands\CheckEntries;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check for unconfirmed entries
Schedule::command(CheckEntries::class)->dailyAt('01:00');
//Schedule::command( CheckEntries::class)->everyMinute();