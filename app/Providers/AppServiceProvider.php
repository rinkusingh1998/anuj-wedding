<?php

namespace App\Providers;

use App\Models\City;
use App\Models\NavigationItem;
use App\Models\SeoSetting;
use App\Models\State;
use App\Support\MediaLibrary;
use App\Support\SiteProfile;
use App\Support\SiteSchema;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Global SEO data
        |--------------------------------------------------------------------------
        | The layout, head links and body scripts all read the same admin managed
        | settings, so a page never has to query the database itself.
        */
        ViewFactory::composer('client.layout', function (View $view): void {
            $settings = SeoSetting::values();

            $view->with('siteSeo', [
                'site_title' => $settings['site_title'],
                'tagline' => $settings['site_tagline'],
                'home_title' => $settings['home_meta_title'],
                'home_description' => $settings['home_meta_description'],
                'keywords' => $settings['meta_keywords'],
                'image' => MediaLibrary::default(),
                'site_verification' => $settings['google_site_verification'],
                'analytics_id' => $settings['google_analytics_id'],
                'tag_manager_id' => $settings['google_tag_manager_id'],
                'analytics_require_consent' => ($settings['analytics_require_consent'] ?? '0') === '1',
            ]);
        });

        ViewFactory::composer('components.head-links', function (View $view): void {
            $view->with([
                'siteSchema' => app(SiteSchema::class)->json(),
                'whatsappNumber' => app(SiteProfile::class)->whatsapp(),
            ]);
        });

        ViewFactory::composer('components.body-scripts', function (View $view): void {
            $settings = SeoSetting::values();

            $view->with([
                'analyticsId' => $settings['google_analytics_id'],
                'tagManagerId' => $settings['google_tag_manager_id'],
                'analyticsRequireConsent' => ($settings['analytics_require_consent'] ?? '0') === '1',
            ]);
        });

        // Business profile and the complete service-area directory are needed
        // on every client view and shared component (service areas, footer,
        // sidebars and location pages).
        ViewFactory::composer(['client.layout', 'client.pages.*', 'components.*'], function (View $view): void {
            $view->with([
                'profile' => app(SiteProfile::class),
                'visibleStates' => static::visibleStatesWithCities(),
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Admin managed navigation
        |--------------------------------------------------------------------------
        */
        ViewFactory::composer('components.header-component', function (View $view): void {
            $view->with('menuItems', $this->navigation('header'));
        });

        ViewFactory::composer('components.sidebar-component', function (View $view): void {
            $menu = $this->navigation('mobile');

            if ($menu === []) {
                $menu = $this->navigation('header');
            }

            $view->with('mobileMenu', $menu);
        });

        ViewFactory::composer('components.footer-component', function (View $view): void {
            $view->with([
                'footerMenu' => $this->navigation('footer'),
                'visibleStates' => static::visibleStatesWithCities(),
            ]);
        });

        ViewFactory::composer(['client.pages.sitemap'], function (View $view): void {
            $view->with('visibleStates', static::visibleStatesWithCities());
        });
    }

    /**
     * A navigation tree for the given menu (root -> group -> links).
     *
     * @return array<int, array{label: string, description: ?string, url: string, icon: ?string, group_icon: ?string, badge: ?string, target: string, group: ?string, is_mega: bool, is_cta: bool, children: array<int, mixed>}>
     */
    protected function navigation(string $menu): array
    {
        return Cache::remember('navigation-'.$menu, now()->addHour(), function () use ($menu): array {
            $items = NavigationItem::query()
                ->menu($menu)
                ->visible()
                ->ordered()
                ->with(['descendants' => fn ($query) => $query->visible()->ordered()])
                ->root()
                ->get();

            return $items->map(fn (NavigationItem $item): array => $this->navigationNode($item))->all();
        });
    }

    /**
     * @return array{label: string, description: ?string, url: string, icon: ?string, group_icon: ?string, badge: ?string, target: string, group: ?string, is_mega: bool, is_cta: bool, children: array<int, mixed>}
     */
    protected function navigationNode(NavigationItem $item): array
    {
        $children = $item->relationLoaded('descendants')
            ? $item->descendants->map(fn (NavigationItem $child): array => $this->navigationNode($child))->all()
            : [];

        return [
            'label' => $item->label,
            'description' => $item->description,
            'url' => $item->resolvedUrl(),
            'icon' => $item->icon,
            'group_icon' => $item->groupIcon(),
            'badge' => $item->badge,
            'target' => $item->target,
            'group' => $item->group,
            'is_mega' => (bool) ($item->meta['is_mega'] ?? false) || collect($children)->contains(fn (array $child): bool => $child['children'] !== []),
            'is_cta' => (bool) ($item->meta['is_cta'] ?? false),
            'children' => $children,
        ];
    }

    /**
     * Public service areas: every visible, published city grouped by state.
     *
     * The admin's "is_service_area" flag still controls indexability and the
     * short footer list, but visitors can browse every published location from
     * the full service-area directory.
     *
     * @return array<int, array{name: string, url: string, cities: array<int, array{name: string, url: string, district: string}>}>
     */
    protected static function visibleStatesWithCities(): array
    {
        return Cache::remember('visible-states-with-all-cities-v2', now()->addHour(), function (): array {
            return State::query()
                ->published()
                ->ordered()
                ->with(['cities' => fn ($query) => $query
                    ->published()
                    ->orderByDesc('is_featured')
                    ->orderByDesc('priority')
                    ->orderBy('name')])
                ->get()
                ->map(fn (State $state): array => [
                    'name' => $state->name,
                    'url' => $state->url(),
                    'cities' => $state->cities
                        ->map(fn (City $city): array => [
                            'name' => $city->name,
                            'url' => $city->url(),
                            'district' => (string) ($city->district ?? ''),
                        ])
                        ->values()
                        ->all(),
                ])
                ->filter(fn (array $state): bool => $state['cities'] !== [])
                ->values()
                ->all();
        });
    }
}
