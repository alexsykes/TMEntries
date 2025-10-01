<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Models\Trial;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//Schedule::command('backup:run')->dailyAt('05:01');

//Schedule::command('app:check-for-reserves')->everyHour();
//Schedule::command('backup:run')->everyMinute();