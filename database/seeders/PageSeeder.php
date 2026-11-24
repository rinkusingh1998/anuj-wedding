<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Website ke saare static pages ki SEO settings.
 *
 * Yahan ek jagah sabhi pages ka default meta title, description aur
 * keywords likha hai. Admin panel > Pages SEO se inhe kabhi bhi badla
 * ja sakta hai — is seeder ko dobara chalane par admin ki changes
 * overwrite nahi hoti.
 *
 * Naya page add karna ho to yahan ek entry daal kar dobara chalayein:
 *   php artisan db:seed --class=Database\\Seeders\\PageSeeder
 */
class PageSeeder extends Seeder
{
    /**
     * @var list<array{name: string, slug: string, priority: int, sort_order: int, is_visible: bool, meta_title: ?string, meta_description: ?string, meta_keywords: ?string}>
     */
    private const PAGES = [
        [
            'name' => 'Home',
            'slug' => 'home',
            'priority' => 100,
            'sort_order' => 10,
            'is_visible' => true,
            // Home ka meta title/description "SEO & Sitemap" page se control hota hai,
            // isliye yahan khaali chhodte hain.
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
        ],
        [
            'name' => 'About Us',
            'slug' => 'about-us',
            'priority' => 70,
            'sort_order' => 20,
            'is_visible' => true,
            'meta_title' => 'About Us | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Learn about Anuj Kumar Wedding Event Planners — a professional wedding and event decoration team serving Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar, Ghaziabad, Hapur, Meerut and Mathura.',
            'meta_keywords' => 'about anuj kumar wedding event planners, wedding decorator uttar pradesh, event decoration team',
        ],
        [
            'name' => 'Services',
            'slug' => 'services',
            'priority' => 90,
            'sort_order' => 30,
            'is_visible' => true,
            'meta_title' => 'Wedding & Event Decoration Services | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Explore our complete range of wedding and event decoration services — haldi, mehendi, chunni ceremony, balloon, flower, stage, entry gate, wedding counters and complete event decoration.',
            'meta_keywords' => 'wedding decoration services, haldi decoration, mehendi decoration, balloon decoration, flower decoration, stage decoration',
        ],
        [
            'name' => 'Gallery',
            'slug' => 'gallery',
            'priority' => 70,
            'sort_order' => 40,
            'is_visible' => true,
            'meta_title' => 'Wedding & Event Decoration Gallery | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Browse photos of our wedding, haldi, mehendi, birthday and event decoration setups. Real decoration work from our published service areas in Uttar Pradesh.',
            'meta_keywords' => 'wedding decoration gallery, decoration photos, haldi decoration images, wedding stage photos',
        ],
        [
            'name' => 'Packages',
            'slug' => 'packages',
            'priority' => 80,
            'sort_order' => 50,
            'is_visible' => true,
            'meta_title' => 'Wedding & Event Decoration Packages | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Compare wedding and event decoration packages for every budget — from small home functions to grand banquet setups. Transparent pricing and fully customisable themes.',
            'meta_keywords' => 'wedding decoration packages, decoration price, wedding decor package, event decoration rates',
        ],
        [
            'name' => 'Blogs',
            'slug' => 'blogs',
            'priority' => 70,
            'sort_order' => 60,
            'is_visible' => true,
            'meta_title' => 'Wedding & Event Decoration Ideas Blog | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Decoration ideas, theme tips and planning guides for weddings, haldi, mehendi and events. Helpful articles from our wedding decoration team in Uttar Pradesh.',
            'meta_keywords' => 'wedding decoration ideas, decoration blog, wedding planning tips, mehendi decoration ideas',
        ],
        [
            'name' => 'FAQ',
            'slug' => 'faq',
            'priority' => 60,
            'sort_order' => 70,
            'is_visible' => true,
            'meta_title' => 'Wedding Decoration FAQs | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Answers to common questions about wedding and event decoration — services, pricing, customisation, booking, service areas and same-day decoration.',
            'meta_keywords' => 'wedding decoration faq, decoration questions, wedding decorator answers',
        ],
        [
            'name' => 'Service Areas',
            'slug' => 'locations',
            'priority' => 80,
            'sort_order' => 80,
            'is_visible' => true,
            'meta_title' => 'Service Areas | Wedding Decoration in Uttar Pradesh & Nearby Cities',
            'meta_description' => 'List of all cities, towns and villages where Anuj Kumar Wedding Event Planners provides wedding and event decoration service. Open your city page and book online.',
            'meta_keywords' => 'wedding decoration service areas, wedding decorator list, uttar pradesh wedding decoration',
        ],
        [
            'name' => 'Contact Us',
            'slug' => 'contact-us',
            'priority' => 80,
            'sort_order' => 90,
            'is_visible' => true,
            'meta_title' => 'Contact Us | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Contact Anuj Kumar Wedding Event Planners for wedding, haldi, mehendi, birthday and event decoration in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar, Ghaziabad, Hapur, Meerut and Mathura.',
            'meta_keywords' => 'contact wedding decorator, wedding decoration booking, event decoration enquiry',
        ],
        [
            'name' => 'Sitemap',
            'slug' => 'sitemap',
            'priority' => 30,
            'sort_order' => 100,
            'is_visible' => true,
            'meta_title' => 'Sitemap | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Complete list of pages on our website — services, packages, blogs, service areas and important information.',
            'meta_keywords' => null,
        ],
        [
            'name' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'priority' => 20,
            'sort_order' => 110,
            'is_visible' => true,
            'meta_title' => 'Privacy Policy | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Read how Anuj Kumar Wedding Event Planners collects, uses and protects the information you share through our website, enquiry forms and communication channels.',
            'meta_keywords' => null,
        ],
        [
            'name' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'priority' => 20,
            'sort_order' => 120,
            'is_visible' => true,
            'meta_title' => 'Terms & Conditions | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Terms and conditions for using our website and booking our wedding and event decoration services in Uttar Pradesh.',
            'meta_keywords' => null,
        ],
        [
            'name' => 'Disclaimer',
            'slug' => 'disclaimer',
            'priority' => 20,
            'sort_order' => 130,
            'is_visible' => true,
            'meta_title' => 'Disclaimer | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Disclaimer for the Anuj Kumar Wedding Event Planners website, covering content, pricing, images and external links.',
            'meta_keywords' => null,
        ],
        [
            'name' => 'Wedding & Event Decoration Guide',
            'slug' => 'wedding-event-decoration-guide',
            'priority' => 60,
            'sort_order' => 140,
            'is_visible' => true,
            'meta_title' => 'Wedding & Event Decoration Guide | Ideas, Themes & Planning Tips',
            'meta_description' => 'A complete guide to wedding and event decoration — choosing themes, colours, flowers, lighting and budgets, and picking the right decoration team for your celebration.',
            'meta_keywords' => 'wedding decoration guide, event decoration ideas, wedding theme guide, decoration planning tips',
        ],
        [
            'name' => 'Blog Detail',
            'slug' => 'blog-detail',
            'priority' => 20,
            'sort_order' => 150,
            // Ye ek design template hai, apna alag URL nahi — isliye noindex.
            'is_visible' => false,
            'meta_title' => 'Blog Article | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Wedding and event decoration article from Anuj Kumar Wedding Event Planners.',
            'meta_keywords' => null,
        ],
        [
            'name' => 'Service Detail',
            'slug' => 'service-detail',
            'priority' => 20,
            'sort_order' => 160,
            'is_visible' => false,
            'meta_title' => 'Service Detail | Anuj Kumar Wedding Event Planners',
            'meta_description' => 'Wedding and event decoration service detail from Anuj Kumar Wedding Event Planners.',
            'meta_keywords' => null,
        ],
    ];

    public function run(): void
    {
        $created = 0;
        $updated = 0;

        foreach (self::PAGES as $page) {
            $existing = Page::query()->where('slug', $page['slug'])->first();

            if ($existing) {
                // Admin ka SEO content aur visibility decision safe rakhte hain.
                $existing->update(['name' => $page['name']]);
                $updated++;

                continue;
            }

            Page::query()->create($page);
            $created++;
        }

        $this->command?->info("Pages: {$created} naye page add hue, {$updated} update hue.");
    }
}
