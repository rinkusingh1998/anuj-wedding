<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Service;
use App\Models\State;
use Database\Seeders\ServiceAreaSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\UttarPradeshSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * "php artisan migrate:fresh --seed" ke baad website turant ready honi chahiye:
 * UP ka page, Khurja ka page, service pages aur sitemap — sab.
 */
class LocationSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ServiceSeeder::class);
        $this->seed(UttarPradeshSeeder::class);
        $this->seed(ServiceAreaSeeder::class);
    }

    public function test_uttar_pradesh_state_is_created_and_visible(): void
    {
        $state = State::query()->where('slug', 'uttar-pradesh')->first();

        $this->assertNotNull($state);
        $this->assertTrue($state->is_visible);
        $this->assertTrue($state->is_default);
    }

    public function test_khurja_is_created_as_a_featured_city(): void
    {
        $city = City::query()->where('slug', 'khurja')->first();

        $this->assertNotNull($city);
        $this->assertSame('Bulandshahr', $city->district);
        $this->assertTrue($city->is_visible);
        $this->assertTrue($city->is_featured);
    }

    public function test_aligarh_and_jewar_are_covered(): void
    {
        $this->assertDatabaseHas('cities', ['slug' => 'aligarh', 'district' => 'Aligarh']);
        $this->assertDatabaseHas('cities', ['slug' => 'jewar', 'district' => 'Gautam Buddh Nagar']);
    }

    public function test_the_same_village_or_city_name_can_exist_in_two_districts(): void
    {
        // Phulpur Azamgarh aur Phulpur Prayagraj dono me hai — dono ke page banne chahiye.
        $this->assertDatabaseHas('cities', ['slug' => 'phulpur', 'district' => 'Azamgarh']);
        $this->assertDatabaseHas('cities', ['slug' => 'phulpur-prayagraj', 'district' => 'Prayagraj']);
    }

    public function test_all_twelve_services_are_created(): void
    {
        $this->assertSame(12, Service::query()->count());
        $this->assertDatabaseHas('services', ['slug' => 'wedding-decoration']);
        $this->assertDatabaseHas('services', ['slug' => 'complete-event-decor']);
    }

    public function test_seeded_city_pages_are_live(): void
    {
        $this->get('/uttar-pradesh')->assertOk();
        $this->get('/uttar-pradesh/khurja')->assertOk();
        $this->get('/uttar-pradesh/khurja/wedding-decoration')->assertOk();
    }

    public function test_seeded_pages_appear_in_the_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/uttar-pradesh/agra', false)
            ->assertSee('/uttar-pradesh/agra/wedding-decoration', false)
            ->assertDontSee('/uttar-pradesh/khurja', false);
    }

    public function test_the_demo_hidden_state_is_not_reachable(): void
    {
        $delhi = State::query()->where('slug', 'delhi')->first();

        $this->assertNotNull($delhi);
        $this->assertFalse($delhi->is_visible);

        $this->get('/delhi')->assertNotFound();
    }

    public function test_import_can_be_run_twice_without_creating_duplicates(): void
    {
        $before = City::query()->count();

        $this->seed(UttarPradeshSeeder::class);

        $this->assertSame($before, City::query()->count());
    }
}
