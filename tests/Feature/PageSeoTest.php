<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SeoSetting;
use App\Services\SitemapService;
use Database\Seeders\PageSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Static pages (Home, About Us, Contact Us, Legal pages waghera) ka SEO.
 *
 *  - Har page ka apna, unique meta title aur description hona chahiye.
 *  - Admin ka likha content hi dikhna chahiye.
 *  - Page hide karte hi noindex + sitemap se bahar.
 */
class PageSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_page_has_a_unique_meta_title_and_description(): void
    {
        $this->seed([PageSeeder::class, SettingsSeeder::class]);

        $titles = [];
        $descriptions = [];

        foreach (Page::query()->visible()->pluck('slug') as $slug) {
            $url = $slug === 'home' ? '/' : '/'.$slug;

            $response = $this->get($url)->assertOk();

            $titles[$slug] = $this->pageTitle($response->getContent());
            $descriptions[$slug] = $this->metaContent($response->getContent(), 'description');
        }

        $this->assertNotContains('', $titles);
        $this->assertSame(count($titles), count(array_unique($titles)), 'Har page ka meta title unique hona chahiye.');

        $this->assertNotContains('', $descriptions);
        $this->assertSame(count($descriptions), count(array_unique($descriptions)), 'Har page ka meta description unique hona chahiye.');
    }

    public function test_admin_meta_title_and_description_are_shown_when_filled(): void
    {
        Page::factory()->create([
            'slug' => 'about-us',
            'name' => 'About Us',
            'meta_title' => 'Custom About Title',
            'meta_description' => 'Custom about description text.',
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('Custom About Title', false)
            ->assertSee('Custom about description text.', false);
    }

    public function test_page_without_a_database_record_returns_not_found(): void
    {
        $this->get('/gallery')->assertNotFound();
    }

    public function test_home_page_uses_home_seo_settings(): void
    {
        $this->seed(PageSeeder::class);

        SeoSetting::put('home_meta_title', 'Home Custom Title');
        SeoSetting::put('home_meta_description', 'Home custom description text.');

        $this->get('/')
            ->assertOk()
            ->assertSee('Home Custom Title', false)
            ->assertSee('Home custom description text.', false);
    }

    public function test_hidden_page_returns_not_found_and_is_not_in_the_sitemap(): void
    {
        $page = Page::factory()->create([
            'slug' => 'gallery',
            'name' => 'Gallery',
            'is_visible' => true,
        ]);

        $this->get('/gallery')->assertOk()->assertSee('index, follow', false);

        $page->update(['is_visible' => false]);

        app(SitemapService::class)->write();

        $this->get('/gallery')->assertNotFound();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/gallery', false);
    }

    public function test_page_seeder_does_not_overwrite_admin_edits(): void
    {
        $page = Page::factory()->create([
            'slug' => 'about-us',
            'name' => 'About Us',
            'meta_title' => 'Admin Written Title',
            'is_visible' => false,
        ]);

        $this->seed(PageSeeder::class);

        $page->refresh();

        $this->assertSame('Admin Written Title', $page->meta_title);
        $this->assertFalse($page->is_visible);
    }

    private function pageTitle(string $html): string
    {
        preg_match('/<title>(.*?)<\/title>/s', $html, $matches);

        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5);
    }

    private function metaContent(string $html, string $name): string
    {
        preg_match('/<meta name="'.preg_quote($name, '/').'" content="(.*?)">/s', $html, $matches);

        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5);
    }
}
