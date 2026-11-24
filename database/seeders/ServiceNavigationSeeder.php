<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Keeps the header/mobile "Services" mega menu in sync with the catalogue.
 *
 * NavigationSeeder only runs on an empty menu. This seeder is safe to run on a
 * live site: it removes links to retired services and adds links for any new
 * service that is not in the menu yet. Admin reordering and labels of existing
 * items are left untouched.
 */
class ServiceNavigationSeeder extends Seeder
{
    /**
     * @var list<string>
     */
    private const RETIRED_URLS = [
        '/services/flower-decoration',
        '/services/stage-backdrop-decoration',
        '/services/entry-decoration',
        '/services/wedding-counters',
    ];

    public function run(): void
    {
        NavigationItem::query()->whereIn('url', self::RETIRED_URLS)->delete();

        $services = Service::query()
            ->visible()
            ->ordered()
            ->with('category')
            ->get();

        foreach (['header', 'mobile'] as $menu) {
            $root = NavigationItem::query()
                ->where('menu', $menu)
                ->where('url', '/services')
                ->first();

            if (! $root) {
                continue;
            }

            $groups = NavigationItem::query()
                ->where('menu', $menu)
                ->where('parent_id', $root->getKey())
                ->get();

            foreach ($services as $service) {
                $url = '/services/'.$service->slug;

                $exists = NavigationItem::query()
                    ->where('menu', $menu)
                    ->where('url', $url)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $group = $groups->firstWhere('group', $service->category?->name) ?: $groups->first();

                if (! $group) {
                    continue;
                }

                NavigationItem::query()->create([
                    'parent_id' => $group->getKey(),
                    'menu' => $menu,
                    'label' => $service->name,
                    'url' => $url,
                    'icon' => $service->icon,
                    'target' => '_self',
                    'is_visible' => true,
                    'sort_order' => 100 + (int) $service->sort_order,
                ]);
            }
        }

        $this->command?->info('Service navigation links synced.');
    }
}
