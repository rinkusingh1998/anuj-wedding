<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Groups the decoration services so the admin panel stays readable.
 *
 * The groups match the main menu sections on the website:
 *   Wedding Functions, Decoration & Styling and Party & Celebration.
 *
 * Re-running is safe: categories are matched by slug and services are only
 * moved when the service still exists.
 */
class ServiceCategorySeeder extends Seeder
{
    /**
     * @var list<array{name: string, slug: string, icon: string, sort_order: int, services: list<string>}>
     */
    private const CATEGORIES = [
        [
            'name' => 'Wedding Functions',
            'slug' => 'wedding-functions',
            'icon' => 'fa-solid fa-ring',
            'sort_order' => 10,
            'services' => [
                'wedding-decoration',
                'engagement-decoration',
                'haldi-decoration',
                'mehendi-decoration',
                'chunni-ceremony',
                'family-function-decoration',
                'honeymoon-decoration',
            ],
        ],
        [
            'name' => 'Decoration & Styling',
            'slug' => 'decor-styling',
            'icon' => 'fa-solid fa-wand-magic-sparkles',
            'sort_order' => 20,
            'services' => [
                'balloon-decoration',
                'fogg-matka',
            ],
        ],
        [
            'name' => 'Party & Celebration',
            'slug' => 'party-celebration',
            'icon' => 'fa-solid fa-champagne-glasses',
            'sort_order' => 30,
            'services' => [
                'birthday-decoration',
                'festival-decoration',
                'complete-event-decor',
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $definition) {
            $category = ServiceCategory::query()->updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'name' => $definition['name'],
                    'icon' => $definition['icon'],
                    'sort_order' => $definition['sort_order'],
                    'is_visible' => true,
                ],
            );

            Service::query()
                ->whereIn('slug', $definition['services'])
                ->update(['service_category_id' => $category->getKey()]);
        }
    }
}
