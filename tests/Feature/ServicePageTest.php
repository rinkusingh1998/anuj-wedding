<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use App\Models\City;
use App\Models\Service;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Service landing pages: /services/{slug}
 *
 *  - Visible service ka apna page banna chahiye (unique meta + schema).
 *  - Hidden ya unknown slug par 404 ki jagah /services par redirect.
 */
class ServicePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_visible_service_has_its_own_landing_page(): void
    {
        $state = State::factory()->create(['name' => 'Uttar Pradesh', 'slug' => 'uttar-pradesh']);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'is_featured' => true,
        ]);

        Service::factory()->create([
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
        ]);

        $response = $this->get('/services/wedding-decoration')->assertOk();

        $html = $response->getContent();

        $this->assertStringContainsString('<title>Wedding Decoration |', $html);
        $this->assertStringContainsString('index, follow', $html);
        $this->assertStringContainsString('"@type":"Service"', $html);
        $this->assertStringContainsString('/uttar-pradesh/khurja"', $html);
    }

    public function test_service_detail_page_shows_image_left_information_right_and_combined_booking(): void
    {
        $state = State::factory()->create(['name' => 'Uttar Pradesh', 'slug' => 'uttar-pradesh']);

        City::factory()->for($state)->create([
            'name' => 'Meerut',
            'slug' => 'meerut',
            'is_featured' => true,
        ]);

        BusinessProfile::query()->create([
            'business_name' => 'Anuj Kumar Wedding Event Planners',
            'phone' => '+91 98765 43210',
            'whatsapp' => '919876543210',
        ]);

        Service::factory()->create([
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
            'description' => 'Complete wedding decoration with stage, entry gate and seating for every guest.',
        ]);

        Service::factory()->create([
            'name' => 'Mehendi Decoration',
            'slug' => 'mehendi-decoration',
        ]);

        $response = $this->get('/services/wedding-decoration')->assertOk();

        $response
            ->assertSee('svc-media', false)
            ->assertSee('svc-content', false)
            ->assertSee('Complete Your Celebration With', false)
            ->assertSee('svc-related-grid', false)
            ->assertSee('Book Wedding Decoration for Your Date', false)
            ->assertDontSee('Our Work', false)
            ->assertSee('https://web.whatsapp.com/send?phone=+919876543210', false)
            ->assertSee(rawurlencode('Wedding Decoration'), false)
            ->assertSee('Mehendi Decoration', false);
    }

    public function test_unknown_service_slug_redirects_to_services_page_instead_of_404(): void
    {
        $this->get('/services/this-service-does-not-exist')->assertRedirect('/services');
    }

    public function test_hidden_service_redirects_to_services_page(): void
    {
        Service::factory()->create([
            'name' => 'Hidden Service',
            'slug' => 'hidden-service',
            'is_visible' => false,
        ]);

        $this->get('/services/hidden-service')->assertRedirect('/services');
    }

    public function test_service_is_listed_in_the_sitemap(): void
    {
        Service::factory()->create([
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/services/wedding-decoration', false);
    }
}
