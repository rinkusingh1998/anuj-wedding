<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

/**
 * Seeds the default header, mobile and footer menus.
 *
 * The seeder never touches an existing menu, so any change an admin makes in
 * the Navigation Menus screen is preserved when the seeder runs again.
 */
class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMenu('header', $this->headerTree());
        $this->seedMenu('mobile', $this->headerTree());
        $this->seedMenu('footer', $this->footerTree());
    }

    /**
     * @param  list<array<string, mixed>>  $tree
     */
    private function seedMenu(string $menu, array $tree): void
    {
        if (NavigationItem::query()->where('menu', $menu)->exists()) {
            return;
        }

        $this->createItems($menu, $tree, null);
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function createItems(string $menu, array $items, ?int $parentId): void
    {
        foreach ($items as $index => $item) {
            $children = $item['children'] ?? [];

            $record = NavigationItem::query()->create([
                'parent_id' => $parentId,
                'menu' => $menu,
                'label' => $item['label'],
                'description' => $item['description'] ?? null,
                'url' => $item['url'] ?? null,
                'route' => $item['route'] ?? null,
                'icon' => $item['icon'] ?? null,
                'badge' => $item['badge'] ?? null,
                'target' => $item['target'] ?? '_self',
                'group' => $item['group'] ?? null,
                'meta' => $item['meta'] ?? null,
                'is_visible' => $item['is_visible'] ?? true,
                'sort_order' => ($index + 1) * 10,
            ]);

            if ($children !== []) {
                $this->createItems($menu, $children, $record->id);
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function headerTree(): array
    {
        return [
            [
                'label' => 'Home',
                'url' => '/',
                'icon' => 'fa-solid fa-house',
            ],
            [
                'label' => 'About Us',
                'url' => '/about-us',
                'icon' => 'fa-solid fa-circle-info',
            ],
            [
                'label' => 'Services',
                'url' => '/services',
                'icon' => 'fa-solid fa-sparkles',
                'meta' => ['is_mega' => true],
                'children' => [
                    [
                        'label' => 'Wedding Functions',
                        'description' => 'Decor for every wedding ritual',
                        'group' => 'Wedding Functions',
                        'icon' => 'fa-solid fa-ring',
                        'meta' => ['group_icon' => 'fa-solid fa-ring'],
                        'children' => [
                            ['label' => 'Wedding Decoration', 'url' => '/services/wedding-decoration', 'icon' => 'fa-solid fa-ring'],
                            ['label' => 'Engagement Decoration', 'url' => '/services/engagement-decoration', 'icon' => 'fa-solid fa-hand-holding-heart'],
                            ['label' => 'Haldi Decoration', 'url' => '/services/haldi-decoration', 'icon' => 'fa-solid fa-sun'],
                            ['label' => 'Mehendi Decoration', 'url' => '/services/mehendi-decoration', 'icon' => 'fa-solid fa-leaf'],
                            ['label' => 'Chunni Ceremony Decoration', 'url' => '/services/chunni-ceremony', 'icon' => 'fa-solid fa-gem'],
                            ['label' => 'Family Function Decoration', 'url' => '/services/family-function-decoration', 'icon' => 'fa-solid fa-people-roof'],
                            ['label' => 'Honeymoon Decoration', 'url' => '/services/honeymoon-decoration', 'icon' => 'fa-solid fa-heart'],
                        ],
                    ],
                    [
                        'label' => 'Decoration & Styling',
                        'description' => 'Balloons and statement entry setups',
                        'group' => 'Decoration & Styling',
                        'icon' => 'fa-solid fa-palette',
                        'meta' => ['group_icon' => 'fa-solid fa-palette'],
                        'children' => [
                            ['label' => 'Balloon Decoration', 'url' => '/services/balloon-decoration', 'icon' => 'fa-solid fa-balloon'],
                            ['label' => 'Fogg & Matka Entry Setup', 'url' => '/services/fogg-matka', 'icon' => 'fa-solid fa-cloud'],
                        ],
                    ],
                    [
                        'label' => 'Party & Celebration',
                        'description' => 'Birthdays, festivals and complete setups',
                        'group' => 'Party & Celebration',
                        'icon' => 'fa-solid fa-champagne-glasses',
                        'meta' => ['group_icon' => 'fa-solid fa-champagne-glasses'],
                        'children' => [
                            ['label' => 'Birthday Decoration', 'url' => '/services/birthday-decoration', 'icon' => 'fa-solid fa-cake-candles'],
                            ['label' => 'Festival Decoration', 'url' => '/services/festival-decoration', 'icon' => 'fa-solid fa-fire'],
                            ['label' => 'Complete Event Decoration', 'url' => '/services/complete-event-decor', 'icon' => 'fa-solid fa-wand-magic-sparkles'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Gallery',
                'url' => '/gallery',
                'icon' => 'fa-solid fa-images',
            ],
            [
                'label' => 'Packages',
                'url' => '/packages',
                'icon' => 'fa-solid fa-gift',
            ],
            [
                'label' => 'Blog',
                'url' => '/blogs',
                'icon' => 'fa-solid fa-newspaper',
            ],
            [
                'label' => 'Get a Quote',
                'url' => '/contact-us',
                'icon' => 'fa-solid fa-arrow-right',
                'meta' => ['is_cta' => true],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function footerTree(): array
    {
        return [
            ['label' => 'About Us', 'url' => '/about-us', 'group' => 'Company'],
            ['label' => 'Services', 'url' => '/services', 'group' => 'Company'],
            ['label' => 'Gallery', 'url' => '/gallery', 'group' => 'Company'],
            ['label' => 'Packages', 'url' => '/packages', 'group' => 'Company'],
            ['label' => 'Blog', 'url' => '/blogs', 'group' => 'Company'],
            ['label' => 'Contact Us', 'url' => '/contact-us', 'group' => 'Company'],

            ['label' => 'Service Areas', 'url' => '/locations', 'group' => 'Explore'],
            ['label' => 'FAQ', 'url' => '/faq', 'group' => 'Explore'],
            ['label' => 'Wedding Guide', 'url' => '/wedding-event-decoration-guide', 'group' => 'Explore'],
            ['label' => 'Sitemap', 'url' => '/sitemap', 'group' => 'Explore'],

            ['label' => 'Privacy Policy', 'url' => '/privacy-policy', 'group' => 'Legal'],
            ['label' => 'Terms & Conditions', 'url' => '/terms-and-conditions', 'group' => 'Legal'],
            ['label' => 'Disclaimer', 'url' => '/disclaimer', 'group' => 'Legal'],
        ];
    }
}
