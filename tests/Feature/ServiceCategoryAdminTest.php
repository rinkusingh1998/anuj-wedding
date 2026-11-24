<?php

namespace Tests\Feature;

use App\Filament\Resources\ServiceCategories\Pages\CreateServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\EditServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\ListServiceCategories;
use App\Filament\Resources\ServiceCategories\RelationManagers\ServicesRelationManager;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Widgets\SiteOverview;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\ServiceCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Services admin me category ke hisaab se group hote hain:
 * "Wedding Functions", "Decoration & Styling", "Party & Celebration".
 */
class ServiceCategoryAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_service_category(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateServiceCategory::class)
            ->fillForm([
                'name' => 'Wedding Functions',
                'slug' => 'wedding-functions',
                'sort_order' => 10,
                'is_visible' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('service_categories', [
            'slug' => 'wedding-functions',
            'name' => 'Wedding Functions',
        ]);
    }

    public function test_category_list_shows_the_number_of_services(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ServiceCategory::factory()->create(['name' => 'Decoration & Styling']);

        Service::factory()->count(2)->create(['service_category_id' => $category->getKey()]);
        Service::factory()->create(['service_category_id' => null]);

        Livewire::test(ListServiceCategories::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$category])
            ->assertTableColumnStateSet('services_count', 2, $category);
    }

    public function test_category_edit_page_lists_only_its_own_services(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ServiceCategory::factory()->create(['name' => 'Wedding Functions']);

        $inside = Service::factory()->create([
            'name' => 'Haldi Decoration',
            'service_category_id' => $category->getKey(),
        ]);

        $outside = Service::factory()->create([
            'name' => 'Balloon Decoration',
            'service_category_id' => null,
        ]);

        Livewire::test(ServicesRelationManager::class, [
            'ownerRecord' => $category,
            'pageClass' => EditServiceCategory::class,
        ])
            ->assertOk()
            ->assertCanSeeTableRecords([$inside])
            ->assertCanNotSeeTableRecords([$outside]);
    }

    public function test_service_form_saves_the_selected_category(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['service_category_id' => null]);

        Livewire::test(EditService::class, [
            'record' => $service->getRouteKey(),
        ])
            ->fillForm(['service_category_id' => $category->getKey()])
            ->call('save')
            ->assertHasNoFormErrors();

        $service->refresh();

        $this->assertSame($category->getKey(), $service->service_category_id);
        $this->assertTrue($service->category->is($category));
    }

    public function test_services_list_is_grouped_by_category(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ServiceCategory::factory()->create(['name' => 'Party & Celebration']);
        $service = Service::factory()->create([
            'name' => 'Balloon Decoration',
            'service_category_id' => $category->getKey(),
        ]);

        Livewire::test(ListServices::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$service])
            ->assertTableColumnStateSet('category.name', $category->name, $service);
    }

    public function test_seeder_groups_the_existing_services_into_categories(): void
    {
        $wedding = Service::factory()->create(['slug' => 'wedding-decoration']);
        $balloon = Service::factory()->create(['slug' => 'balloon-decoration']);

        $this->seed(ServiceCategorySeeder::class);

        $this->assertSame(3, ServiceCategory::query()->count());
        $this->assertSame('Wedding Functions', $wedding->fresh()->category->name);
        $this->assertSame('Decoration & Styling', $balloon->fresh()->category->name);
    }

    public function test_dashboard_shows_the_website_overview_cards(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(SiteOverview::class)
            ->assertOk()
            ->assertSee('New enquiries')
            ->assertSee('Pages')
            ->assertSee('Services')
            ->assertSee('Media files');
    }
}
