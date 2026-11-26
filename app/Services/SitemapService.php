<?php

namespace App\Services;

use App\Models\City;
use App\Models\Locality;
use App\Models\Package;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\State;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Generates /sitemap.xml and /robots.txt.
 *
 * Only indexable records are included:
 *  - published + visible
 *  - robots_index = true
 *  - cities must be active service areas (the eight primary regions)
 *  - localities need a published city and state
 * Draft, noindex, deleted and non-service-area URLs never appear.
 */
final class SitemapService
{
    public const MAX_URLS_PER_FILE = 40000;

    private const CACHE_KEY = 'sitemap-xml';

    public function xml(): string
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), fn (): string => $this->build());
    }

    /**
     * Rebuild sitemap.xml (+ parts) and robots.txt.
     *
     * @return int Number of URLs written
     */
    public function write(): int
    {
        Cache::forget(self::CACHE_KEY);

        $urls = $this->urls();
        $parts = array_chunk($urls, self::MAX_URLS_PER_FILE);

        $this->removeOldFiles();

        if (count($parts) <= 1) {
            File::put(public_path('sitemap.xml'), $this->toXml($parts[0] ?? []));
        } else {
            $entries = [];

            foreach ($parts as $key => $part) {
                $file = 'sitemap-'.($key + 1).'.xml';

                File::put(public_path($file), $this->toXml($part));

                $entries[] = $this->urlEntry($this->baseUrl('/'.$file), now()->toAtomString(), 'weekly', '0.5');
            }

            File::put(public_path('sitemap.xml'), $this->toIndexXml($entries));
        }

        $this->writeRobotsTxt();

        return count($urls);
    }

    /**
     * Queue a rebuild once per request.
     */
    public static function scheduleWrite(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(AppServiceProvider::LOCATIONS_CACHE_KEY);

        defer(static fn () => app(self::class)->write(), 'sitemap-write');
    }

    public function writeRobotsTxt(): void
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /livewire',
            '',
            'Sitemap: '.$this->baseUrl('/sitemap.xml'),
            '',
        ];

        File::put(public_path('robots.txt'), implode("\n", $lines));
    }

    /**
     * @return list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>
     */
    public function urls(): array
    {
        $urls = [];

        // --- Designed pages -------------------------------------------------
        foreach (Page::query()->indexable()->ordered()->get() as $page) {
            $urls[] = $this->urlEntry(
                loc: $page->url(),
                lastmod: $page->updated_at?->toAtomString(),
                changefreq: $page->slug === 'home' ? 'daily' : 'monthly',
                priority: $this->priority($page->priority),
            );
        }

        // --- Services -------------------------------------------------------
        $services = Service::query()->indexable()->ordered()->get();

        foreach ($services as $service) {
            $urls[] = $this->urlEntry(
                loc: $service->url(),
                lastmod: $service->updated_at?->toAtomString(),
                changefreq: 'weekly',
                priority: $this->priority($service->priority),
            );
        }

        // --- Packages -------------------------------------------------------
        foreach (Package::query()->indexable()->ordered()->get() as $package) {
            $urls[] = $this->urlEntry(
                loc: $package->url(),
                lastmod: $package->updated_at?->toAtomString(),
                changefreq: 'monthly',
                priority: $this->priority($package->priority),
            );
        }

        // --- Blog -----------------------------------------------------------
        foreach (PostCategory::query()->indexable()->ordered()->get() as $category) {
            $urls[] = $this->urlEntry(
                loc: $category->url(),
                lastmod: $category->updated_at?->toAtomString(),
                changefreq: 'weekly',
                priority: '0.4',
            );
        }

        foreach (Post::query()->indexable()->ordered()->get() as $post) {
            $urls[] = $this->urlEntry(
                loc: $post->url(),
                lastmod: ($post->updated_at ?? $post->published_at)?->toAtomString(),
                changefreq: 'monthly',
                priority: $this->priority($post->priority),
            );
        }

        // --- Locations ------------------------------------------------------
        $states = State::query()
            ->indexable()
            ->with([
                'cities' => fn ($query) => $query->indexable()->serviceArea()->ordered(),
                'localities' => fn ($query) => $query->indexable()->with('city')->ordered(),
            ])
            ->ordered()
            ->get();

        foreach ($states as $state) {
            // A state with no active service-area city has nothing useful to
            // index, so it stays out of the sitemap even when it is visible.
            if ($state->cities->isEmpty()) {
                continue;
            }

            $urls[] = $this->urlEntry(
                loc: $state->url(),
                lastmod: $state->updated_at?->toAtomString(),
                changefreq: 'weekly',
                priority: $this->priority($state->priority),
            );

            foreach ($state->cities as $city) {
                $urls[] = $this->urlEntry(
                    loc: $city->url(),
                    lastmod: $city->updated_at?->toAtomString(),
                    changefreq: 'weekly',
                    priority: $this->priority($city->priority),
                );

                foreach ($services as $service) {
                    $urls[] = $this->urlEntry(
                        loc: $city->serviceUrl($service),
                        lastmod: $service->updated_at?->toAtomString(),
                        changefreq: 'monthly',
                        priority: $this->priority(max(20, $city->priority - 10)),
                    );
                }
            }

            foreach ($state->localities as $locality) {
                if (! $locality->city?->is_service_area) {
                    continue;
                }

                $urls[] = $this->urlEntry(
                    loc: $locality->url(),
                    lastmod: $locality->updated_at?->toAtomString(),
                    changefreq: 'monthly',
                    priority: $this->priority($locality->priority),
                );
            }
        }

        return $urls;
    }

    /**
     * @return array{pages: int, states: int, cities: int, localities: int, services: int, posts: int, packages: int, urls: int}
     */
    public function stats(): array
    {
        return [
            'pages' => Page::query()->indexable()->count(),
            'states' => State::query()
                ->indexable()
                ->whereHas('cities', fn ($query) => $query->indexable()->serviceArea())
                ->count(),
            'cities' => City::query()->indexable()->serviceArea()->count(),
            'localities' => Locality::query()->indexable()->count(),
            'services' => Service::query()->indexable()->count(),
            'posts' => Post::query()->indexable()->count(),
            'packages' => Package::query()->indexable()->count(),
            'urls' => count($this->urls()),
        ];
    }

    private function build(): string
    {
        $parts = array_chunk($this->urls(), self::MAX_URLS_PER_FILE);

        if (count($parts) <= 1) {
            return $this->toXml($parts[0] ?? []);
        }

        $entries = [];

        foreach (array_keys($parts) as $key) {
            $entries[] = $this->urlEntry(
                $this->baseUrl('/sitemap-'.($key + 1).'.xml'),
                now()->toAtomString(),
                'weekly',
                '0.5',
            );
        }

        return $this->toIndexXml($entries);
    }

    /**
     * @return array{loc: string, lastmod: ?string, changefreq: string, priority: string}
     */
    private function urlEntry(string $loc, ?string $lastmod, string $changefreq, string $priority): array
    {
        return ['loc' => $loc, 'lastmod' => $lastmod, 'changefreq' => $changefreq, 'priority' => $priority];
    }

    /**
     * @param  list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>  $urls
     */
    private function toXml(array $urls): string
    {
        $lines = [];

        foreach ($urls as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>'.e($url['loc']).'</loc>';

            if ($url['lastmod']) {
                $lines[] = '    <lastmod>'.e($url['lastmod']).'</lastmod>';
            }

            $lines[] = '    <changefreq>'.$url['changefreq'].'</changefreq>';
            $lines[] = '    <priority>'.$url['priority'].'</priority>';
            $lines[] = '  </url>';
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .implode("\n", $lines)
            ."\n".'</urlset>';
    }

    /**
     * @param  list<array{loc: string, lastmod: ?string, changefreq: string, priority: string}>  $entries
     */
    private function toIndexXml(array $entries): string
    {
        $lines = array_map(
            fn (array $entry): string => '  <sitemap><loc>'.e($entry['loc']).'</loc><lastmod>'
                .e((string) $entry['lastmod']).'</lastmod></sitemap>',
            $entries,
        );

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .implode("\n", $lines)
            ."\n".'</sitemapindex>';
    }

    private function priority(int $priority): string
    {
        return number_format(max(1, min(100, $priority)) / 100, 1);
    }

    private function baseUrl(string $path = ''): string
    {
        return rtrim(config('app.url'), '/').$path;
    }

    private function removeOldFiles(): void
    {
        foreach (File::glob(public_path('sitemap-*.xml')) as $file) {
            File::delete($file);
        }

        File::delete(public_path('sitemap.xml'));
    }
}
