<?php

namespace App\Observers;

use App\Models\PostCategory;
use App\Services\SitemapService;
use App\Support\SlugRedirector;

/**
 * Blog category save/delete hone par sitemap refresh karta hai aur slug change
 * par purane URL ka 301 redirect banata hai.
 */
class PostCategoryObserver
{
    public function updating(PostCategory $category): void
    {
        SlugRedirector::handle($category);
    }

    public function saved(PostCategory $category): void
    {
        SitemapService::scheduleWrite();
    }

    public function deleted(PostCategory $category): void
    {
        SitemapService::scheduleWrite();
    }
}
