<?php

namespace App\Observers;

use App\Models\Locality;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Locality page save/delete hone par sitemap.xml refresh hota hai.
 */
class LocalityObserver
{
    public function updating(Locality $locality): void
    {
        SlugRedirector::handle($locality);
    }

    public function saved(Locality $locality): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(Locality $locality): void
    {
        SitemapService::scheduleWrite();
    }
}
