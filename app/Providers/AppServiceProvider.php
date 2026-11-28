<?php

namespace App\Providers;

use App\Models\City;
use App\Models\NavigationItem;
use App\Models\SeoSetting;
use App\Models\State;
use App\Support\CanonicalUrl;
use App\Support\MediaLibrary;
use App\Support\SiteProfile;
use App\Support\SiteSchema;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Shared cache key for the public state / city directory.
     *
     * The homepage service-area block, footer, locations page and SEO stats all
     * read this list, so every place that clears the cache must use this key.
     */
    public const LOCATIONS_CACHE_KEY = 'visible-states-with-all-cities-v3';

    /**
     * Shared prefix for the cached header / footer / mobile menus.
     */
    public const NAVIGATION_CACHE_PREFIX = 'navigation-v2-';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->forceCanonicalHost();

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
     * The cache only stores host-independent data. Real URLs are resolved on
     * every request, so a cached menu can never leak the server IP address
     * into links (for example when a health check hits the raw IP).
     *
     * @return array<int, array{label: string, description: ?string, url: string, route: ?string, icon: ?string, group_icon: ?string, badge: ?string, target: string, group: ?string, is_mega: bool, is_cta: bool, children: array<int, mixed>}>
     */
    protected function navigation(string $menu): array
    {
        $items = Cache::remember(self::NAVIGATION_CACHE_PREFIX.$menu, now()->addHour(), function () use ($menu): array {
            $items = NavigationItem::query()
                ->menu($menu)
                ->visible()
                ->ordered()
                ->with(['descendants' => fn ($query) => $query->visible()->ordered()])
                ->root()
                ->get();

            return $items->map(fn (NavigationItem $item): array => $this->navigationNode($item))->all();
        });

        return $this->resolveNavigationUrls($items);
    }

    /**
     * @return array{label: string, description: ?string, url: string, route: ?string, icon: ?string, group_icon: ?string, badge: ?string, target: string, group: ?string, is_mega: bool, is_cta: bool, children: array<int, mixed>}
     */
    protected function navigationNode(NavigationItem $item): array
    {
        $children = $item->relationLoaded('descendants')
            ? $item->descendants->map(fn (NavigationItem $child): array => $this->navigationNode($child))->all()
            : [];

        return [
            'label' => $item->label,
            'description' => $item->description,
            'url' => (string) $item->url,
            'route' => filled($item->route) ? (string) $item->route : null,
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
     * Turn the cached navigation data into real, current URLs.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    protected function resolveNavigationUrls(array $items): array
    {
        return array_map(fn (array $item): array => $this->resolveNavigationItem($item), $items);
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function resolveNavigationItem(array $item): array
    {
        $item['url'] = $this->navigationUrl(
            $item['route'] ?? null,
            (string) ($item['url'] ?? ''),
        );

        $item['children'] = array_map(
            fn (array $child): array => $this->resolveNavigationItem($child),
            $item['children'] ?? [],
        );

        return $item;
    }

    /**
     * Build an internal URL at render time. Absolute links that point at a raw
     * IP address are treated as internal and converted to a relative path, so
     * old cached menus immediately stop sending visitors to the server IP.
     */
    protected function navigationUrl(?string $route, string $url): string
    {
        if (filled($route) && Route::has($route)) {
            return route($route);
        }

        if ($url === '') {
            return url('/');
        }

        if (str_starts_with($url, '#')) {
            return $url;
        }

        if (preg_match('#^https?://#i', $url)) {
            $host = (string) parse_url($url, PHP_URL_HOST);

            // Same-site links stay relative so they always follow the live
            // domain (and never keep a stale http:// or server-IP host).
            $canonicalHost = (string) (CanonicalUrl::host() ?? parse_url((string) config('app.url'), PHP_URL_HOST));
            $hostKey = strtolower((string) preg_replace('/^www\./i', '', $host));
            $canonicalKey = strtolower((string) preg_replace('/^www\./i', '', $canonicalHost));

            $isSameSite = $host !== '' && (
                filter_var($host, FILTER_VALIDATE_IP)
                || ($canonicalKey !== '' && $hostKey === $canonicalKey)
            );

            if ($isSameSite) {
                $path = (string) (parse_url($url, PHP_URL_PATH) ?: '/');
                $query = parse_url($url, PHP_URL_QUERY);

                return $query ? $path.'?'.$query : $path;
            }

            return $url;
        }

        return url($url);
    }

    /**
     * Never generate links for the raw server IP address.
     *
     * Some deployments are reachable both through the domain and through the
     * server IP (health checks, direct access). Forcing the configured root URL
     * keeps canonical links, forms and cached data on the real domain.
     */
    protected function forceCanonicalHost(): void
    {
        if ($this->app->runningUnitTests()) {
            return;
        }

        $canonical = CanonicalUrl::base();

        if ($canonical === null) {
            return;
        }

        URL::forceRootUrl($canonical);

        if (parse_url($canonical, PHP_URL_SCHEME) === 'https') {
            URL::forceScheme('https');
        }
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
        return Cache::remember(self::LOCATIONS_CACHE_KEY, now()->addHour(), function (): array {
            return State::query()
                ->published()
                ->ordered()
                ->with(['cities' => fn ($query) => $query
                    ->published()
                    ->orderByDesc('is_featured')
                    ->orderByDesc('priority')
                    ->orderBy('name')])
                ->get()
                ->map(function (State $state): array {
                    // Give every city its parent state so the relative path is
                    // built without an extra query per city.
                    $state->cities->each(fn (City $city) => $city->setRelation('state', $state));

                    return [
                        'name' => $state->name,
                        // Relative links keep this cache valid on any host.
                        'url' => '/'.ltrim($state->path(), '/'),
                        'cities' => $state->cities
                            ->map(fn (City $city): array => [
                                'name' => $city->name,
                                'url' => '/'.ltrim($city->path(), '/'),
                                'district' => (string) ($city->district ?? ''),
                            ])
                            ->values()
                            ->all(),
                    ];
                })
                ->filter(fn (array $state): bool => $state['cities'] !== [])
                ->values()
                ->all();
        });
    }
}
