<?php

namespace Tests\Feature;

use App\Filament\Pages\SeoSitemapSettings;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\States\Pages\ListStates;
use App\Models\BusinessProfile;
use App\Models\City;
use App\Models\Page;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Admin panel: bina login koi andar nahi aa sakta, aur login ke baad
 * states / cities / services / SEO page sab khulte hain.
 */
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_requires_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function test_admin_can_open_states_cities_and_services_lists(): void
    {
        $user = User::factory()->create();

        State::factory()->create();
        City::factory()->create();

        $this->actingAs($user)->get('/admin/states')->assertOk();
        $this->actingAs($user)->get('/admin/cities')->assertOk();
        $this->actingAs($user)->get('/admin/services')->assertOk();
        $this->actingAs($user)->get('/admin/pages')->assertOk();
        $this->actingAs($user)->get('/admin/seo-sitemap-settings')->assertOk();
        $this->actingAs($user)->get('/admin/website-guide')->assertOk();
    }

    public function test_admin_can_open_create_and_edit_forms(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['states', 'cities', 'services', 'pages'] as $resource) {
            $this->get('/admin/'.$resource.'/create')->assertOk();
        }

        $records = [
            'states' => State::factory()->create(),
            'cities' => City::factory()->create(),
            'services' => Service::factory()->create(),
            'pages' => Page::factory()->create(),
        ];

        foreach ($records as $resource => $record) {
            $this->get('/admin/'.$resource.'/'.$record->getRouteKey().'/edit')->assertOk();
        }
    }

    public function test_admin_can_open_the_business_profile_form(): void
    {
        $this->actingAs(User::factory()->create());

        $profile = BusinessProfile::query()->create([
            'business_name' => 'Anuj Kumar Wedding Event Planners',
            'footer_about' => 'Footer about text.',
        ]);

        $this->get('/admin/business-profiles/'.$profile->getRouteKey().'/edit')->assertOk();
    }

    public function test_admin_can_upload_a_page_image_and_save_a_content_section(): void
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('public');

        $page = Page::factory()->create(['slug' => 'about-us', 'name' => 'About Us']);

        $jpeg = base64_decode(
            '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////'
            .'2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAA'
            .'AAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgB'
            .'AQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBAB'
            .'AAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABD/xAAU'
            .'EQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/EP/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8Q/8QAFBABAAAAAAAAAAAAAAAA'
            .'AAAAAP/aAAgBAQABPxB//9k=',
        );

        Livewire::test(EditPage::class, [
            'record' => $page->getRouteKey(),
        ])
            ->fillForm([
                'image' => UploadedFile::fake()->createWithContent('banner.jpg', $jpeg),
                'content' => [
                    'sections' => [[
                        'eyebrow' => 'Our Story',
                        'heading' => 'Uploaded section heading',
                        'body' => '<p>Uploaded section body.</p>',
                        'image_position' => 'right',
                    ]],
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $page->refresh();

        $this->assertNotNull($page->image);
        $this->assertStringStartsWith('pages/', $page->image);
        $this->assertStringContainsString('storage/pages/', $page->imageUrl());
        $this->assertSame('Uploaded section heading', $page->content['sections'][0]['heading']);
    }

    public function test_saving_a_page_keeps_an_existing_cdn_image_url(): void
    {
        $this->actingAs(User::factory()->create());

        $url = 'https://cdn.example.com/banner.jpg';

        $page = Page::factory()->create([
            'slug' => 'about-us',
            'name' => 'About Us',
            'image' => $url,
        ]);

        Livewire::test(EditPage::class, [
            'record' => $page->getRouteKey(),
        ])
            ->fillForm(['image' => [$url]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($url, $page->fresh()->image);
    }

    public function test_states_table_shows_records(): void
    {
        $this->actingAs(User::factory()->create());

        $state = State::factory()->create(['name' => 'Uttar Pradesh']);

        Livewire::test(ListStates::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$state]);
    }

    public function test_cities_table_shows_the_visibility_toggle_state(): void
    {
        $this->actingAs(User::factory()->create());

        $visible = City::factory()->create(['is_visible' => true]);
        $hidden = City::factory()->hidden()->create();

        Livewire::test(ListCities::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$visible, $hidden])
            ->assertTableColumnStateSet('is_visible', true, $visible)
            ->assertTableColumnStateSet('is_visible', false, $hidden);
    }

    public function test_admin_can_open_services_list(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(ListServices::class)
            ->assertOk();
    }

    public function test_admin_can_save_seo_settings(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(SeoSitemapSettings::class)
            ->fillForm([
                'site_title' => 'Anuj Kumar Wedding Event Planners',
                'whatsapp_number' => '917302015048',
                'google_analytics_id' => 'G-TEST123456',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('G-TEST123456', SeoSetting::value('google_analytics_id'));
        $this->assertSame('917302015048', SeoSetting::value('whatsapp_number'));
    }
}
