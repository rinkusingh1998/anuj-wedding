<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Sets the correct template key for the designed pages.
 *
 * The page template tells PageController which designed layout to render. It is
 * only filled when a page still has the generic "default" value, so a template
 * that an admin selected manually is never overwritten.
 */
class PageTemplateSeeder extends Seeder
{
    /**
     * @var array<string, string>
     */
    private const TEMPLATES = [
        'home' => 'home',
        'about-us' => 'about',
        'services' => 'services',
        'gallery' => 'gallery',
        'packages' => 'packages',
        'blogs' => 'blogs',
        'faq' => 'faq',
        'locations' => 'locations',
        'contact-us' => 'contact',
        'sitemap' => 'sitemap',
        'privacy-policy' => 'legal',
        'terms-and-conditions' => 'legal',
        'disclaimer' => 'legal',
        'wedding-event-decoration-guide' => 'guide',
    ];

    public function run(): void
    {
        foreach (self::TEMPLATES as $slug => $template) {
            Page::query()
                ->where('slug', $slug)
                ->where(function ($query): void {
                    $query->whereNull('template')
                        ->orWhere('template', '')
                        ->orWhere('template', 'default');
                })
                ->update(['template' => $template]);
        }
    }
}
