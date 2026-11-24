<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Page;
use App\Models\Service;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Location pages ka behaviour:
 *  - Jo page admin panel me "Show" hai wo khulta hai.
 *  - Jo "Hide" hai wo 404 ho jata hai (Google use crawl nahi kar sakta).
 */
class LocationPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        Page::factory()->create(['slug' => 'home', 'name' => 'Home']);

        $this->get('/')
            ->assertOk();
    }

    public function test_visible_state_page_loads(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        $this->get('/uttar-pradesh')
            ->assertOk()
            ->assertSee($state->name, false)
            ->assertSee('loc-detail-media', false)
            ->assertSee('loc-detail-body', false);
    }

    public function test_hidden_state_page_returns_not_found(): void
    {
        State::factory()->hidden()->create([
            'name' => 'Delhi',
            'slug' => 'delhi',
        ]);

        $this->get('/delhi')
            ->assertNotFound();
    }

    public function test_visible_city_page_loads(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'district' => 'Bulandshahr',
        ]);

        $this->get('/uttar-pradesh/khurja')
            ->assertOk()
            ->assertSee('Khurja', false)
            ->assertSee('loc-detail-media', false)
            ->assertSee('loc-detail-body', false);
    }

    public function test_hidden_city_page_returns_not_found(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->hidden()->create([
            'name' => 'Jewar',
            'slug' => 'jewar',
        ]);

        $this->get('/uttar-pradesh/jewar')
            ->assertNotFound();
    }

    public function test_city_page_is_not_found_when_its_state_is_hidden(): void
    {
        $state = State::factory()->hidden()->create([
            'name' => 'Delhi',
            'slug' => 'delhi',
        ]);

        City::factory()->for($state)->create([
            'name' => 'New Delhi',
            'slug' => 'new-delhi',
        ]);

        $this->get('/delhi/new-delhi')
            ->assertNotFound();
    }

    public function test_service_in_city_page_loads(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
        ]);

        Service::factory()->create([
            'name' => 'Wedding Decoration',
            'slug' => 'wedding-decoration',
        ]);

        $this->get('/uttar-pradesh/khurja/wedding-decoration')
            ->assertOk()
            ->assertSee('Wedding Decoration in Khurja', false);
    }

    public function test_hidden_service_pages_return_not_found(): void
    {
        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
        ]);

        Service::factory()->hidden()->create([
            'name' => 'Birthday Decoration',
            'slug' => 'birthday-decoration',
        ]);

        $this->get('/uttar-pradesh/khurja/birthday-decoration')
            ->assertNotFound();
    }

    public function test_unknown_state_and_city_return_not_found(): void
    {
        $this->get('/not-a-real-page')
            ->assertNotFound();

        $this->get('/uttar-pradesh/not-a-real-city')
            ->assertNotFound();
    }

    public function test_locations_directory_loads(): void
    {
        Page::factory()->create(['slug' => 'locations', 'name' => 'Service Areas']);

        $state = State::factory()->create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
        ]);

        City::factory()->for($state)->create([
            'name' => 'Khurja',
            'slug' => 'khurja',
            'is_service_area' => true,
        ]);

        $this->get('/locations')
            ->assertOk()
            ->assertSee('Khurja', false);
    }

    public function test_old_service_links_redirect_to_services_page(): void
    {
        $this->get('/services/wedding-decoration')
            ->assertRedirect('/services');
    }
}
