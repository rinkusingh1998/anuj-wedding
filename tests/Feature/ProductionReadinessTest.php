<?php

namespace Tests\Feature;

use App\Filament\Pages\GlobalSettings;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use App\Support\MediaUsage;
use App\Support\SeoManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

/**
 * Production-readiness checks added after the CMS audit:
 * automatic slug redirects, safe media deletion, real image dimensions in
 * social metadata and admin/editor role separation.
 */
class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_changing_a_page_slug_creates_a_301_redirect(): void
    {
        $page = Page::factory()->create([
            'name' => 'Old Page',
            'slug' => 'old-page',
            'is_visible' => true,
        ]);

        $page->update(['slug' => 'new-page']);

        $this->assertDatabaseHas('redirects', [
            'from_path' => '/old-page',
            'to_path' => '/new-page',
            'status_code' => 301,
            'is_active' => true,
        ]);
    }

    public function test_media_metadata_is_filled_automatically(): void
    {
        Storage::fake('public');

        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
        );

        Storage::disk('public')->put('media/pixel.png', $png);

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => 'media/pixel.png',
        ]);

        $this->assertSame('pixel.png', $media->filename);
        $this->assertSame('image/png', $media->mime_type);
        $this->assertSame(1, $media->width);
        $this->assertSame(1, $media->height);
        $this->assertSame(Storage::disk('public')->url('media/pixel.png'), $media->url);
    }

    public function test_media_used_by_a_page_cannot_be_force_deleted(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/used.png', 'not-a-real-image');

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => 'media/used.png',
        ]);

        Page::factory()->create([
            'name' => 'Media Page',
            'slug' => 'media-page',
            'image' => 'media/used.png',
            'is_visible' => true,
        ]);

        $this->assertNotEmpty(app(MediaUsage::class)->usages($media));

        $this->expectException(RuntimeException::class);
        $media->forceDelete();
    }

    public function test_unused_media_can_be_force_deleted(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/unused.png', 'not-a-real-image');

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => 'media/unused.png',
        ]);

        $media->forceDelete();

        $this->assertDatabaseMissing('media', ['id' => $media->getKey()]);
        Storage::disk('public')->assertMissing('media/unused.png');
    }

    public function test_social_image_is_absolute_and_uses_the_real_dimensions(): void
    {
        Storage::fake('public');

        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
        );

        Storage::disk('public')->put('media/social.png', $png);

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => 'media/social.png',
        ]);

        $media->updateQuietly(['width' => 1200, 'height' => 630]);

        $page = Page::factory()->create([
            'name' => 'Social Page',
            'slug' => 'social-page',
            'image' => 'media/social.png',
            'meta_title' => 'Social Page Title',
            'is_visible' => true,
        ]);

        $seo = app(SeoManager::class)->forModel($page);

        $this->assertSame(asset('storage/media/social.png'), $seo['og']['image']);
        $this->assertSame(1200, $seo['og_image_width']);
        $this->assertSame(630, $seo['og_image_height']);
        $this->assertStringStartsWith('http', $seo['og']['image']);
    }

    public function test_hidden_page_can_be_previewed_only_by_a_signed_in_user(): void
    {
        Page::factory()->create([
            'name' => 'About Us',
            'slug' => 'about-us',
            'is_visible' => false,
        ]);

        $this->get('/about-us')->assertNotFound();
        $this->get('/about-us?preview=1')->assertNotFound();

        $this->actingAs(User::factory()->create())
            ->get('/about-us?preview=1')
            ->assertOk()
            ->assertSee('noindex, nofollow', false);
    }

    public function test_editor_cannot_open_admin_only_screens(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->get(GlobalSettings::getUrl())
            ->assertForbidden();

        $this->actingAs($editor)
            ->get(UserResource::getUrl())
            ->assertForbidden();
    }

    public function test_admin_can_open_admin_only_screens(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(GlobalSettings::getUrl())
            ->assertOk();

        $this->actingAs($admin)
            ->get(UserResource::getUrl())
            ->assertOk();

        $this->actingAs($admin)
            ->get(UserResource::getUrl('create'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(UserResource::getUrl('edit', ['record' => $admin]))
            ->assertOk();
    }

    public function test_admin_can_open_the_media_library_and_page_copy_editor(): void
    {
        $admin = User::factory()->create();

        $page = Page::factory()->create([
            'name' => 'Copy Page',
            'slug' => 'copy-page',
            'content' => ['copy' => ['text_1' => 'Editable copy']],
            'is_visible' => true,
        ]);

        $this->actingAs($admin)
            ->get(MediaResource::getUrl())
            ->assertOk();

        $this->actingAs($admin)
            ->get(PageResource::getUrl('edit', ['record' => $page]))
            ->assertOk()
            ->assertSee('Built-in Design Text');
    }

    public function test_built_in_page_copy_is_rendered_from_the_database_and_empty_values_hide_it(): void
    {
        $page = Page::factory()->create([
            'name' => 'About Us',
            'slug' => 'about-us',
            'content' => ['copy' => ['text_1' => 'Database Copy Heading']],
            'is_visible' => true,
        ]);

        $this->get('/about-us')
            ->assertOk()
            ->assertSee('Database Copy Heading');

        $page->update(['content' => ['copy' => ['text_1' => '']]]);

        $this->get('/about-us')
            ->assertOk()
            ->assertDontSee('Database Copy Heading');
    }
}
