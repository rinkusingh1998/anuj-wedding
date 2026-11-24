<?php

namespace App\Observers;

use App\Models\State;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * State add / update / delete hone par sitemap.xml apne aap refresh ho jata hai.
 */
class StateObserver
{
    public function updating(State $state): void
    {
        SlugRedirector::handle($state);
    }

    public function saved(State $state): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(State $state): void
    {
        SitemapService::scheduleWrite();
    }
}
