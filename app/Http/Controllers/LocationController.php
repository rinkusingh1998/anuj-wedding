<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Locality;
use App\Models\Page;
use App\Models\Service;
use App\Models\State;
use App\Support\SeoContent;
use App\Support\SeoManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * State / city / locality / service-in-city landing pages.
 *
 * URL structure:
 *   /{state}                        e.g. /uttar-pradesh
 *   /{state}/{city}                 e.g. /uttar-pradesh/agra
 *   /{state}/{city}/{service}       e.g. /uttar-pradesh/agra/wedding-decoration
 *   /{state}/{city}/area/{locality} e.g. /uttar-pradesh/agra/tajganj
 *
 * Only published records are served. Hiding a parent (state or city) hides all
 * of its children automatically.
 */
class LocationController extends Controller
{
    public function __construct(
        private readonly SeoContent $content,
        private readonly SeoManager $seo,
    ) {}

    /**
     * Catch-all route (registered last, so it never shadows another route).
     */
    public function fallback(Request $request, PageController $pages): Response|View
    {
        $segments = array_values(array_filter(
            explode('/', $request->path()),
            fn (string $segment): bool => $segment !== '',
        ));

        return match (count($segments)) {
            1 => $this->singleSegment($segments[0], $pages),
            2 => $this->cityPage($segments[0], $segments[1]),
            3 => $this->servicePage($segments[0], $segments[1], $segments[2]),
            4 => $this->localityPage($segments[0], $segments[1], $segments[2], $segments[3]),
            default => abort(404),
        };
    }

    /**
     * HTML directory of every public service area (internal linking).
     */
    public function directory(): View
    {
        $page = Page::query()->where('slug', 'locations')->first();

        abort_unless($page && $page->isPublished(), 404);

        $states = State::query()
            ->published()
            ->with([
                'cities' => fn ($query) => $query->published()->ordered(),
                'localities' => fn ($query) => $query->published()->ordered()->with('city'),
            ])
            ->ordered()
            ->get();

        // The directory lists cities and localities together, so each
        // locality needs its parent state for the public URL.
        $states->each(function (State $state): void {
            $state->localities->each(fn (Locality $locality) => $locality->setRelation('state', $state));
        });

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $page->name, 'url' => $page->url()],
        ];

        return view('client.pages.locations', [
            'states' => $states,
            'page' => $page,
            'seo' => $this->seo->forModel($page, [
                'breadcrumbs' => $breadcrumbs,
                'faqs' => $page->faqs ?? [],
            ]),
        ]);
    }

    private function singleSegment(string $slug, PageController $pages): Response|View
    {
        $state = State::query()->published()->where('slug', $slug)->first();

        if ($state) {
            return $this->statePage($state);
        }

        return $pages->allpages($slug);
    }

    private function statePage(State $state): View
    {
        $cities = $state->cities()->published()->ordered()->get();

        $localities = Locality::query()
            ->publiclyVisible()
            ->where('state_id', $state->getKey())
            ->whereIn('city_id', $cities->pluck('id'))
            ->with('city')
            ->ordered()
            ->get();

        $localities->each(fn (Locality $locality) => $locality->setRelation('state', $state));

        $services = Service::query()->visible()->ordered()->get();

        return view('client.pages.location.state', [
            'state' => $state,
            'cities' => $cities,
            'localities' => $localities,
            'services' => $services,
            'seo' => $this->content->state($state, $cities),
        ]);
    }

    private function cityPage(string $stateSlug, string $citySlug): View
    {
        $state = State::query()->published()->where('slug', $stateSlug)->firstOrFail();

        $city = $state->cities()->published()->where('slug', $citySlug)->firstOrFail();
        $city->setRelation('state', $state);

        $nearby = $this->nearbyCities($state, $city);

        return view('client.pages.location.city', [
            'state' => $state,
            'city' => $city,
            'nearbyCities' => $nearby,
            'stateCities' => $this->topCities($state, $city),
            'localities' => $city->localities()->published()->ordered()->get(),
            'services' => Service::query()->visible()->ordered()->get(),
            'seo' => $this->content->city($state, $city, $nearby),
        ]);
    }

    private function servicePage(string $stateSlug, string $citySlug, string $serviceSlug): View
    {
        $state = State::query()->published()->where('slug', $stateSlug)->firstOrFail();
        $city = $state->cities()->published()->where('slug', $citySlug)->firstOrFail();
        $city->setRelation('state', $state);

        $service = Service::query()->visible()->where('slug', $serviceSlug)->firstOrFail();
        $nearby = $this->nearbyCities($state, $city);

        return view('client.pages.location.service', [
            'state' => $state,
            'city' => $city,
            'service' => $service,
            'nearbyCities' => $nearby,
            'stateCities' => $this->topCities($state, $city),
            'services' => Service::query()->visible()->ordered()->get(),
            'seo' => $this->content->cityService($state, $city, $service, $nearby),
        ]);
    }

    /**
     * /{state}/{city}/area/{locality}
     */
    private function localityPage(string $stateSlug, string $citySlug, string $segment, string $localitySlug): View
    {
        abort_unless($segment === 'area', 404);

        $state = State::query()->published()->where('slug', $stateSlug)->firstOrFail();
        $city = $state->cities()->published()->where('slug', $citySlug)->firstOrFail();
        $city->setRelation('state', $state);

        $locality = $city->localities()->published()->where('slug', $localitySlug)->firstOrFail();
        $locality->setRelation('state', $state);
        $locality->setRelation('city', $city);

        return view('client.pages.location.locality', [
            'state' => $state,
            'city' => $city,
            'locality' => $locality,
            'services' => Service::query()->visible()->ordered()->get(),
            'nearbyLocalities' => $city->localities()
                ->published()
                ->whereKeyNot($locality->getKey())
                ->ordered()
                ->limit(12)
                ->get(),
            'seo' => $this->content->locality($state, $city, $locality, collect()),
        ]);
    }

    /**
     * @return Collection<int, City>
     */
    private function topCities(State $state, City $current): Collection
    {
        return $state->cities()
            ->published()
            ->whereKeyNot($current->getKey())
            ->orderByDesc('is_featured')
            ->orderByDesc('priority')
            ->orderBy('name')
            ->limit(30)
            ->get();
    }

    /**
     * @return Collection<int, City>
     */
    private function nearbyCities(State $state, City $city): Collection
    {
        $sameDistrict = $state->cities()
            ->published()
            ->whereKeyNot($city->getKey())
            ->when($city->district, fn ($query) => $query->where('district', $city->district))
            ->orderByDesc('is_featured')
            ->orderByDesc('priority')
            ->orderBy('name')
            ->limit(12)
            ->get();

        if ($sameDistrict->count() >= 6) {
            return $sameDistrict;
        }

        $extra = $state->cities()
            ->published()
            ->whereKeyNot($city->getKey())
            ->whereNotIn('id', $sameDistrict->pluck('id'))
            ->orderByDesc('is_featured')
            ->orderByDesc('priority')
            ->orderBy('name')
            ->limit(12 - $sameDistrict->count())
            ->get();

        return $sameDistrict->concat($extra);
    }
}
