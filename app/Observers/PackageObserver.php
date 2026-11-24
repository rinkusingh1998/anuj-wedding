<?php

namespace App\Observers;

use App\Models\Package;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Package publish/unpublish/delete hone par sitemap.xml refresh hota hai.
 */
class PackageObserver
{
    public function updating(Package $package): void
    {
        SlugRedirector::handle($package);
    }

    public function saved(Package $package): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(Package $package): void
    {
        SitemapService::scheduleWrite();
    }
}
