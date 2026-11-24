<?php

namespace App\Observers;

use App\Models\City;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * City (city / town / tehsil / village) save ya delete hote hi
 * sitemap.xml dobara ban jata hai. Page hide/show karne par bhi
 * sitemap turant update ho jata hai.
 */
class CityObserver
{
    public function updating(City $city): void
    {
        SlugRedirector::handle($city);
    }

    public function saved(City $city): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(City $city): void
    {
        SitemapService::scheduleWrite();
    }
}
