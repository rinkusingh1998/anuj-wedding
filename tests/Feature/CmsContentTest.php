<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\Page;
use App\Models\Service;
use App\Models\State;
use App\Support\MediaLibrary;
use Database\Seeders\CmsSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\UttarPradeshSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Website ka saara content database se aata hai.
 *
 * Ye tests proof hain ki admin panel me content/image badalne par
 * public page turant wahi naya data dikhata hai.
 */
class CmsContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            ServiceSeeder::class,
            UttarPradeshSeeder::class,
            CmsSeeder::class,
        ]);
    }

    public function test_home_page_renders_hero_content_from_the_database(): void
    {
        $page = Page::query()->where('slug', 'home')->firstOrFail();

        $page->update([
            'content' => [
                ...$page->content,
                'hero_slides' => [[
                    'image' => 'https://cdn.example.com/custom-hero.jpg',
                    'tag' => 'Custom Database Tag',
                    'heading' => 'Custom <span>Database Heading</span>',
                    'description' => 'Custom database description.',
                    'primary_label' => 'Book Now',
                    'primary_url' => '/contact-us',
                    'highlights' => ['Custom Database Highlight'],
                ]],
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Database Tag')
            ->assertSee('Custom <span>Database Heading</span>', false)
            ->assertSee('Custom Database Highlight')
            ->assertSee('https://cdn.example.com/custom-hero.jpg', false);
    }

    public function test_page_og_image_comes_from_the_database(): void
    {
        Page::query()->where('slug', 'about-us')->firstOrFail()->update([
            'og_image' => 'https://cdn.example.com/custom-og.jpg',
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('<meta property="og:image" content="https://cdn.example.com/custom-og.jpg">', false);
    }

    public function test_page_gallery_images_come_from_the_database(): void
    {
        Page::query()->where('slug', 'about-us')->firstOrFail()->update([
            'gallery' => [[
                'image' => 'https://cdn.example.com/custom-gallery.jpg',
                'title' => 'Custom Gallery Title',
                'category' => 'Wedding Decor',
            ]],
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('https://cdn.example.com/custom-gallery.jpg', false);
    }

    public function test_faq_page_renders_questions_from_the_database(): void
    {
        Page::query()->where('slug', 'faq')->firstOrFail()->update([
            'faqs' => [[
                'category' => 'wedding',
                'question' => 'Is this a custom database question?',
                'answer' => 'Yes, this answer is stored in the database.',
            ]],
        ]);

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Is this a custom database question?')
            ->assertSee('Yes, this answer is stored in the database.');
    }

    public function test_uttar_pradesh_page_uses_database_seo_and_faqs(): void
    {
        State::query()->where('slug', 'uttar-pradesh')->firstOrFail()->update([
            'meta_title' => 'Custom Uttar Pradesh Title',
            'heading' => 'Custom Uttar Pradesh Heading',
            'faqs' => [[
                'question' => 'Custom state level question?',
                'answer' => 'Custom state level answer.',
            ]],
        ]);

        $this->get('/uttar-pradesh')
            ->assertOk()
            ->assertSee('<title>Custom Uttar Pradesh Title</title>', false)
            ->assertSee('Custom Uttar Pradesh Heading')
            ->assertSee('Custom state level question?')
            ->assertSee('Custom state level answer.');
    }

    public function test_contact_details_come_from_the_business_profile(): void
    {
        BusinessProfile::query()->firstOrFail()->update([
            'phone' => '+91 90000 00000',
            'whatsapp' => '919000000000',
            'email' => 'hello@example.com',
            'address' => 'Custom Address, Bulandshahr, Uttar Pradesh',
        ]);

        cache()->forget('site-business-profile');

        $this->get('/contact-us')
            ->assertOk()
            ->assertSee('+91 90000 00000')
            ->assertSee('hello@example.com')
            ->assertSee('Custom Address, Bulandshahr, Uttar Pradesh')
            ->assertSee('https://web.whatsapp.com/send?phone=+919000000000', false);
    }

    public function test_whatsapp_links_use_whatsapp_web_on_desktop_and_wa_me_on_mobile(): void
    {
        BusinessProfile::query()->firstOrFail()->update([
            'whatsapp' => '919000000000',
        ]);

        cache()->forget('site-business-profile');

        $this->get('/contact-us')
            ->assertOk()
            ->assertSee('https://web.whatsapp.com/send?phone=+919000000000', false);

        $this->withHeader(
            'User-Agent',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        )
            ->get('/contact-us')
            ->assertOk()
            ->assertSee('https://wa.me/+919000000000', false);
    }

    public function test_about_story_renders_from_page_content_and_replaces_the_address_placeholder(): void
    {
        $page = Page::query()->where('slug', 'about-us')->firstOrFail();

        $page->update([
            'content' => [
                ...$page->content,
                'about_story' => '<p>Custom about story at {address}.</p>',
            ],
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('Custom about story at', false)
            ->assertDontSee('{address}', false);
    }

    public function test_page_content_sections_and_uploaded_images_render_from_the_database(): void
    {
        $page = Page::query()->where('slug', 'about-us')->firstOrFail();

        $page->update([
            'content' => [
                ...$page->content,
                'sections' => [[
                    'eyebrow' => 'Our Story',
                    'heading' => 'A custom section heading',
                    'body' => '<p>Custom section body.</p>',
                    'image' => 'pages/sections/custom-section.jpg',
                    'image_position' => 'right',
                    'button_label' => 'Talk To Us',
                    'button_url' => '/contact-us',
                ]],
            ],
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('A custom section heading')
            ->assertSee('<p>Custom section body.</p>', false)
            ->assertSee('Talk To Us')
            ->assertSee(asset('storage/pages/sections/custom-section.jpg'), false);
    }

    public function test_uploaded_and_cdn_image_paths_are_normalised(): void
    {
        $this->assertSame(asset('storage/pages/banner.jpg'), MediaLibrary::url('pages/banner.jpg'));
        $this->assertSame(asset('storage/pages/banner.jpg'), MediaLibrary::url('storage/pages/banner.jpg'));
        $this->assertSame(asset('assets/logo/logo2.png'), MediaLibrary::url('assets/logo/logo2.png'));
        $this->assertSame('https://cdn.example.com/banner.jpg', MediaLibrary::url('https://cdn.example.com/banner.jpg'));
        $this->assertNull(MediaLibrary::url(null));
    }

    public function test_page_can_replace_the_built_in_design_with_custom_sections(): void
    {
        $page = Page::query()->where('slug', 'about-us')->firstOrFail();

        $page->update([
            'content' => [
                ...$page->content,
                'replace_template' => true,
                'sections' => [[
                    'heading' => 'Only custom content',
                    'body' => '<p>This page is fully managed from the admin panel.</p>',
                    'image_position' => 'none',
                ]],
            ],
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('Only custom content')
            ->assertSee('<p>This page is fully managed from the admin panel.</p>', false)
            ->assertDontSee('class="about-page"', false);
    }

    public function test_service_content_sections_render_on_the_service_page(): void
    {
        $service = Service::query()->firstOrFail();

        $service->update([
            'content' => [
                'sections' => [[
                    'eyebrow' => 'Service Detail',
                    'heading' => 'A custom service section',
                    'body' => '<p>Service section body.</p>',
                    'image_position' => 'none',
                ]],
            ],
        ]);

        $this->get('/services/'.$service->slug)
            ->assertOk()
            ->assertSee('A custom service section')
            ->assertSee('<p>Service section body.</p>', false);
    }

    public function test_cms_seeder_keeps_delhi_hidden(): void
    {
        State::query()->where('slug', 'delhi')->update(['is_visible' => true]);

        $this->seed(CmsSeeder::class);

        $this->assertFalse(
            State::query()->where('slug', 'delhi')->firstOrFail()->is_visible,
        );

        $this->get('/delhi')->assertNotFound();
    }
}
