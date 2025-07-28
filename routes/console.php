<?php

use App\Console\Commands\ProcessOneDgCampaigns;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command(ProcessOneDgCampaigns::class)
//     ->everyFiveMinutes();

Schedule::command('cache:clear')->dailyAt('02:00');
