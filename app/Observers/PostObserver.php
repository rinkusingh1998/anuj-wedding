<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Blog post publish/unpublish/delete hone par sitemap.xml refresh hota hai.
 */
class PostObserver
{
    public function updating(Post $post): void
    {
        SlugRedirector::handle($post);
    }

    public function saved(Post $post): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(Post $post): void
    {
        SitemapService::scheduleWrite();
    }
}
