<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Service hide/show karne par uske saare "{service} in {city}" pages
 * sitemap se add/remove ho jate hain.
 */
class ServiceObserver
{
    public function updating(Service $service): void
    {
        SlugRedirector::handle($service);
    }

    public function saved(Service $service): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(Service $service): void
    {
        SitemapService::scheduleWrite();
    }
}
