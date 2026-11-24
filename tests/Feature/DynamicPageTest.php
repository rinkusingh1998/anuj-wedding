<?php

namespace Tests\Feature;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\City;
use App\Models\Enquiry;
use App\Models\Media;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\State;
use App\Models\User;
use Database\Seeders\ServiceAreaSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Completes the CMS audit: a page created in the admin panel must render
 * without a new Blade file, flexible blocks must be visible, uploads must reach
 * the Media Library, duplicate enquiries must not be stored twice and unknown
 * URLs must return the branded 404 page.
 */
class DynamicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_page_created_in_the_admin_panel_is_publicly_rendered(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreatePage::class)
            ->fillForm([
                'name' => 'Anniversary Decorations',
                'slug' => 'anniversary-decorations',
                'template' => 'default',
                'is_visible' => true,
                'heading' => 'Anniversary Decoration At Home',
                'intro_content' => '<p>Database driven introduction.</p>',
                'content' => [
                    'body' => '<p>Database driven body copy.</p>',
                    'sections' => [[
                        'heading' => 'A section created in the admin panel',
                        'body' => '<p>Section body.</p>',
                        'image_position' => 'none',
                    ]],
                ],
                'faqs' => [[
                    'question' => 'Do you decorate at home?',
                    'answer' => 'Yes, home celebrations are supported.',
                ]],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $page = Page::query()->where('slug', 'anniversary-decorations')->firstOrFail();

        $this->assertSame('default', $page->template);

        $this->get('/anniversary-decorations')
            ->assertOk()
            ->assertSee('Anniversary Decoration At Home', false)
            ->assertSee('Database driven introduction.', false)
            ->assertSee('Database driven body copy.', false)
            ->assertSee('A section created in the admin panel', false)
            ->assertSee('Do you decorate at home?', false)
            ->assertSee('FAQPage', false)
            ->assertSee('<link rel="canonical" href="'.url('/anniversary-decorations').'">', false);
    }

    public function test_a_page_can_use_a_designed_template_without_its_own_blade_file(): void
    {
        Page::factory()->create([
            'name' => 'Our Story',
            'slug' => 'our-story',
            'template' => 'about',
            'heading' => 'A designed template with database content',
            'is_visible' => true,
        ]);

        $this->get('/our-story')
            ->assertOk()
            ->assertSee('A designed template with database content', false);
    }

    public function test_hidden_pages_still_return_404_and_the_branded_error_page(): void
    {
        Page::factory()->create([
            'name' => 'Draft Page',
            'slug' => 'draft-page',
            'is_visible' => false,
        ]);

        $this->get('/draft-page')->assertNotFound();

        $this->get('/a-url-that-does-not-exist')
            ->assertNotFound()
            ->assertSee("couldn't find that page", false);
    }

    public function test_flexible_blocks_are_rendered_on_the_page(): void
    {
        $page = Page::factory()->create([
            'name' => 'Block Page',
            'slug' => 'block-page',
            'template' => 'default',
            'is_visible' => true,
        ]);

        PageBlock::query()->create([
            'page_id' => $page->id,
            'key' => 'story',
            'type' => 'text',
            'label' => 'Our story',
            'fields' => [
                'heading' => 'A block heading from the admin panel',
                'body' => '<p>A block body from the admin panel.</p>',
            ],
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        PageBlock::query()->create([
            'page_id' => $page->id,
            'key' => 'hidden',
            'type' => 'text',
            'fields' => ['heading' => 'This hidden block must not render'],
            'is_visible' => false,
            'sort_order' => 2,
        ]);

        $this->get('/block-page')
            ->assertOk()
            ->assertSee('A block heading from the admin panel', false)
            ->assertSee('A block body from the admin panel.', false)
            ->assertDontSee('This hidden block must not render', false);
    }

    public function test_an_image_uploaded_on_a_page_is_added_to_the_media_library(): void
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('public');

        $page = Page::factory()->create(['slug' => 'upload-page', 'name' => 'Upload Page']);

        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
        );

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'image' => UploadedFile::fake()->createWithContent('banner.png', $png),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $path = (string) $page->refresh()->image;

        $this->assertNotSame('', $path);
        $this->assertDatabaseHas('media', ['path' => $path]);
        $this->assertNotNull(Media::query()->where('path', $path)->first()?->publicUrl());
    }

    public function test_an_existing_media_image_can_be_selected_for_a_page(): void
    {
        $this->actingAs(User::factory()->create());

        $page = Page::factory()->create(['slug' => 'library-page', 'name' => 'Library Page']);

        $media = Media::query()->create([
            'url' => 'https://example.com/library-image.jpg',
            'filename' => 'library-image.jpg',
            'title' => 'Library image',
            'collection' => 'general',
        ]);

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->callAction(
                TestAction::make('chooseFromMediaLibrary')->schemaComponent('image'),
                data: ['media_id' => $media->getKey()],
            )
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('https://example.com/library-image.jpg', $page->refresh()->image);
    }

    public function test_duplicate_enquiries_are_not_stored_twice(): void
    {
        $payload = [
            'name' => 'Test Customer',
            'phone' => '9876543210',
            'email' => 'customer@example.com',
            'service' => 'Wedding Decoration',
            'event_type' => 'Wedding',
            'event_date' => now()->addMonth()->toDateString(),
            'event_time' => '18:00',
            'city' => 'Meerut',
            'event_address' => 'Civil Lines, Meerut',
            'message' => 'Need decoration for a wedding.',
            'source_page' => 'contact',
            'source_url' => url('/contact-us'),
        ];

        $this->post('/enquiry', $payload)->assertRedirect();
        $this->post('/enquiry', $payload)->assertRedirect();

        $this->assertSame(1, Enquiry::query()->count());
    }

    public function test_only_the_eight_primary_service_areas_are_marked_for_public_listings(): void
    {
        $state = State::factory()->create(['name' => 'Uttar Pradesh', 'slug' => 'uttar-pradesh']);

        foreach (['Agra', 'Aligarh', 'Bulandshahr', 'Ghaziabad', 'Hapur', 'Meerut', 'Mathura', 'Noida'] as $name) {
            City::factory()->for($state)->create([
                'name' => $name,
                'slug' => str($name)->slug()->toString(),
                'is_service_area' => false,
            ]);
        }

        $this->seed(ServiceAreaSeeder::class);

        $serviceAreas = City::query()
            ->where('is_service_area', true)
            ->orderBy('name')
            ->pluck('name')
            ->all();

        $this->assertSame([
            'Agra',
            'Aligarh',
            'Bulandshahr',
            'Gautam Buddh Nagar',
            'Ghaziabad',
            'Hapur',
            'Mathura',
            'Meerut',
        ], $serviceAreas);

        $this->assertFalse((bool) City::query()->where('slug', 'noida')->firstOrFail()->is_service_area);

        // Non-service-area cities stay in the database and keep their page.
        $this->get('/uttar-pradesh/noida')->assertOk();
    }
}
