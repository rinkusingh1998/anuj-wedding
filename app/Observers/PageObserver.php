<?php

namespace App\Observers;

use App\Models\Page;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Static page add / update / delete hone par sitemap.xml apne aap refresh ho jata hai.
 */
class PageObserver
{
    public function updating(Page $page): void
    {
        SlugRedirector::handle($page);
    }

    public function saved(Page $page): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(Page $page): void
    {
        SitemapService::scheduleWrite();
    }
}
