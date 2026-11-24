<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Service;
use App\Models\State;
use App\Services\SitemapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sitemap sirf visible pages ka hona chahiye.
 * Page hide karte hi sitemap se hat jata hai - yehi Google ko batata hai
 * ki us page ko index nahi karna hai.
 */
class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_visible_pages_only(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'is_service_area' => true,
        ]);

        City::factory()->for($state)->hidden()->create([
            'name' => 'Jewar',
            'slug' => 'jewar',
        ]);

        State::factory()->hidden()->create([
            'name' => 'Delhi',
            'slug' => 'delhi',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertSee('/uttar-pradesh', false)
            ->assertSee('/uttar-pradesh/khurja', false)
            ->assertDontSee('/uttar-pradesh/jewar', false)
            ->assertDontSee('/delhi', false);
    }

    public function test_sitemap_lists_service_pages_for_visible_cities(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'is_service_area' => true,
        ]);

        Service::factory()->create([
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/uttar-pradesh/khurja/wedding-decoration', false);
    }

    public function test_generator_returns_expected_url_count(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->count(2)->create(['is_service_area' => true]);

        Service::factory()->count(3)->create();

        $urls = app(SitemapService::class)->urls();

        $locations = array_column($urls, 'loc');

        // 2 city pages + 2 cities x 3 services = 6 service pages.
        $this->assertCount(2 + 6, array_filter(
            $locations,
            fn (string $location): bool => str_contains($location, '/uttar-pradesh/'),
        ));
    }

    public function test_non_service_area_cities_are_kept_out_of_the_sitemap(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Agra',
            'slug' => 'agra',
            'is_service_area' => true,
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'is_service_area' => false,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/uttar-pradesh/agra', false)
            ->assertDontSee('/uttar-pradesh/khurja', false);
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.config('app.url').'/sitemap.xml', false)
            ->assertSee('Disallow: /admin', false);
    }
}
