<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Service;
use App\Support\SeoContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Standalone service landing pages, jaise:
 *   /services/wedding-decoration
 *   /services/haldi-decoration
 *
 * Har service ka apna page hota hai, isliye "{service} in Uttar Pradesh"
 * jaise keywords bhi rank karte hain.
 *
 * Agar slug kisi visible service se match na kare to page 404 karne ki
 * jagah /services par redirect kar dete hain (purane links na tootein).
 */
class ServicePageController extends Controller
{
    public function __construct(private readonly SeoContent $seo) {}

    public function show(string $service): View|RedirectResponse
    {
        $model = Service::query()->visible()->where('slug', $service)->first();

        if (! $model) {
            return redirect('/services', 301);
        }

        // Sirf un states ke cities jinhe admin panel me "Show" kiya gaya hai.
        $cities = City::query()
            ->visible()
            ->whereHas('state', fn ($query) => $query->where('is_visible', true))
            ->with('state:id,slug,is_visible')
            ->ordered()
            ->get();

        $services = Service::query()->visible()->ordered()->get();

        return view('client.pages.services.show', [
            'service' => $model,
            'services' => $services,
            'cities' => $cities,
            'featuredCities' => $cities->where('is_featured', true)->take(24)->values(),
            'seo' => $this->seo->service($model, $cities),
        ]);
    }
}
