<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Page;
use App\Support\SeoManager;
use Illuminate\Contracts\View\View;

/**
 * Decoration package detail pages: /packages/{slug}
 *
 * The listing itself is the designed /packages page.
 */
class PackageController extends Controller
{
    public function __construct(private readonly SeoManager $seo) {}

    public function show(string $slug): View
    {
        $package = Package::query()->published()->where('slug', $slug)->firstOrFail();

        return view('client.pages.package-detail', [
            'package' => $package,
            'otherPackages' => Package::query()
                ->published()
                ->whereKeyNot($package->getKey())
                ->ordered()
                ->limit(3)
                ->get(),
            'seo' => $this->seo->forModel($package, [
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => 'Packages', 'url' => url('/packages')],
                    ['name' => $package->name, 'url' => $package->url()],
                ],
                'schema' => [[
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $package->name,
                    'description' => $package->seoDescription(),
                    'image' => $package->imageUrl(),
                    'url' => $package->url(),
                    'provider' => ['@id' => url('/').'#business'],
                ]],
            ]),
        ]);
    }

    /**
     * Used by the designed /packages page.
     */
    public function indexData(): array
    {
        $page = Page::query()->where('slug', 'packages')->firstOrFail();

        return [
            'page' => $page,
            'packages' => Package::query()->published()->ordered()->get(),
            'seo' => $this->seo->forModel($page, [
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => url('/')],
                    ['name' => $page->name, 'url' => $page->url()],
                ],
                'faqs' => $page->faqs ?? [],
            ]),
        ];
    }
}
