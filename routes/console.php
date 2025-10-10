<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('resumes:update-matches')->everyFiveMinutes();

Schedule::command('notify:expiring-certificates')->everyFiveMinutes();

Schedule::command('certificates:update-expired')->dailyAt('00:00');