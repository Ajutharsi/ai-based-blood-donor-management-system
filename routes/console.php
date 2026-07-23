<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');




// Run AI eligibility re-check every night at midnight
Schedule::command('ai:recheck-eligibility')
         ->dailyAt('00:00')
         ->withoutOverlapping()
         ->runInBackground();