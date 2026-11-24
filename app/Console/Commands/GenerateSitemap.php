<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sitemap:generate')]
#[Description('sitemap.xml aur robots.txt dobara generate karta hai (sirf visible pages ke saath)')]
class GenerateSitemap extends Command
{
    public function handle(SitemapService $sitemap): int
    {
        $count = $sitemap->write();

        $this->info("Sitemap generate ho gaya — total {$count} URLs.");

        return self::SUCCESS;
    }
}
