<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackagePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_listing_renders_packages_from_the_database(): void
    {
        Page::factory()->create([
            'name' => 'Packages',
            'slug' => 'packages',
        ]);

        Package::query()->create([
            'name' => 'Database Celebration',
            'slug' => 'database-celebration',
            'label' => 'Database Package',
            'description' => 'A package loaded from MySQL.',
            'features' => ['Feature one', 'Feature two'],
            'is_visible' => true,
        ]);

        $this->get('/packages')
            ->assertOk()
            ->assertSee('Database Celebration')
            ->assertSee('Feature one')
            ->assertDontSee('Haldi Decoration Package');
    }

    public function test_detail_templates_are_not_served_as_standalone_pages(): void
    {
        Page::factory()->create(['name' => 'Blog Detail', 'slug' => 'blog-detail']);
        Page::factory()->create(['name' => 'Service Detail', 'slug' => 'service-detail']);

        $this->get('/blog-detail')->assertNotFound();
        $this->get('/service-detail')->assertNotFound();
    }

    public function test_package_detail_page_renders_database_content(): void
    {
        Package::query()->create([
            'name' => 'Database Celebration',
            'slug' => 'database-celebration',
            'label' => 'Database Package',
            'badge' => 'Popular',
            'description' => 'A package loaded from MySQL.',
            'features' => ['Feature one', 'Feature two'],
            'is_visible' => true,
        ]);

        Package::query()->create([
            'name' => 'Another Celebration',
            'slug' => 'another-celebration',
            'label' => 'Another Package',
            'description' => 'Another package from MySQL.',
            'is_visible' => true,
        ]);

        $this->get('/packages/database-celebration')
            ->assertOk()
            ->assertSee('Database Celebration')
            ->assertSee('Popular')
            ->assertSee('Feature one')
            ->assertSee('Another Celebration')
            ->assertSee('data-cta="package"', false);
    }
}
