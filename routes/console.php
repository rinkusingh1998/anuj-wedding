<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Sitemap
|--------------------------------------------------------------------------
|
| Roz raat sitemap.xml + robots.txt dobara ban jate hain, taaki
| Google ko hamesha fresh URLs milein.
|
*/
Schedule::command('sitemap:generate')
    ->dailyAt('02:30')
    ->withoutOverlapping();
