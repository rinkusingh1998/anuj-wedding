@extends('client.layout')

@php
    $totalCities = $states->sum(fn ($state): int => $state->cities->count());

    $totalLocalities = $states->sum(fn ($state): int => $state->localities
        ->whereIn('city_id', $state->cities->pluck('id'))
        ->count());

    $totalLocations = $totalCities + $totalLocalities;

    $totalDistricts = $states->sum(fn ($state): int => $state->cities
        ->pluck('district')
        ->filter()
        ->unique()
        ->count());
@endphp

@section('content')
<main class="locations-page">
    <section class="contact-hero-section locations-hero-section" aria-labelledby="locationsHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">{{ $page->name }}</span>
                </nav>

                <span class="contact-eyebrow">Local Coverage</span>

                <h1 id="locationsHeroTitle">{!! $seo['heading'] ?? '' !!}</h1>

                @if (filled($seo['intro'] ?? null))
                    <p>{!! $seo['intro'] !!}</p>
                @endif

                <div class="contact-hero-actions">
                    <a href="#locationsDirectory" class="contact-primary-btn">
                        Browse Locations
                        <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}" class="contact-outline-btn">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Check Availability
                    </a>
                </div>

                <div class="location-hero-stats">
                    <div><strong>{{ $totalLocations }}</strong><span>Published locations</span></div>
                    <div><strong>{{ $totalCities }}</strong><span>Cities &amp; villages</span></div>
                    <div><strong>{{ $totalLocalities }}</strong><span>Villages &amp; areas</span></div>
                    <div><strong>{{ $totalDistricts }}</strong><span>Districts covered</span></div>
                    <div><strong>{{ $states->count() }}</strong><span>State {{ $states->count() === 1 ? 'directory' : 'directories' }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="locations-directory-section" id="locationsDirectory" aria-labelledby="locationsListTitle">
        <div class="container">
            <div class="all-services-heading all-services-heading-center">
                <span class="all-services-eyebrow">Service Directory</span>
                <h2 id="locationsListTitle">{{ site_label('service_areas') ?: 'Our Service Areas' }}</h2>

                @if (filled(site_setting('service_areas_intro')))
                    <p>{{ site_setting('service_areas_intro') }}</p>
                @endif

                <div class="locations-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <label class="visually-hidden" for="locationDirectorySearch">Search locations</label>
                    <input id="locationDirectorySearch"
                           type="search"
                           placeholder="Search a city, town, village or area"
                           autocomplete="off"
                           data-location-filter>
                    <span data-location-count>{{ $totalLocations }} areas</span>
                </div>
            </div>

            @foreach ($states as $state)
                @if ($state->cities->isNotEmpty())
                    @php
                        $districts = $state->cities->groupBy(fn ($city): string => $city->district ?: $state->name);
                        $stateLocalities = $state->localities->whereIn('city_id', $state->cities->pluck('id'));
                        $stateLocations = $state->cities->count() + $stateLocalities->count();
                    @endphp

                    <div class="locations-state-block">
                        <div class="locations-state-head">
                            <div>
                                <span>State Directory</span>
                                <h2>
                                    <a href="{{ $state->url() }}">{{ $state->name }}</a>
                                </h2>
                            </div>
                            <strong>{{ $stateLocations }} locations</strong>
                        </div>

                        @foreach ($districts as $district => $cities)
                            @php
                                $districtLocalities = $stateLocalities->whereIn('city_id', $cities->pluck('id'));
                            @endphp

                            <div class="locations-district-block" data-location-group>
                                <h3>{{ $district }}</h3>

                                <div class="locations-city-grid">
                                    @foreach ($cities as $city)
                                        <a href="{{ $city->url() }}"
                                           class="locations-city-card"
                                           data-location-name="{{ strtolower($city->name.' '.$district.' '.$state->name) }}">
                                            <span class="locations-city-icon">
                                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                            </span>
                                            <span class="locations-city-copy">
                                                <strong>{{ $city->name }}</strong>
                                                @if (filled($city->sub_heading))
                                                    <small>{{ $city->sub_heading }}</small>
                                                @endif
                                            </span>
                                            <span class="locations-city-link">
                                                View area
                                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>

                                @if ($districtLocalities->isNotEmpty())
                                    <div class="locations-localities-block" data-location-group>
                                        <h4>Villages &amp; Areas</h4>

                                        <div class="locations-city-grid">
                                            @foreach ($districtLocalities as $locality)
                                                <a href="{{ $locality->url() }}"
                                                   class="locations-city-card locations-city-card--area"
                                                   data-location-name="{{ strtolower($locality->name.' '.($locality->city?->name ?? '').' '.$district.' '.$state->name) }}">
                                                    <span class="locations-city-icon">
                                                        <i class="fa-solid fa-house" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="locations-city-copy">
                                                        <strong>{{ $locality->name }}</strong>
                                                        <small>
                                                            {{ $locality->city?->name }}
                                                            &middot;
                                                            {{ \App\Models\Locality::TYPES[$locality->type] ?? 'Area' }}
                                                        </small>
                                                    </span>
                                                    <span class="locations-city-link">
                                                        View area
                                                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach

            <p class="locations-empty" data-location-empty hidden>
                No matching location found. Try another spelling or send us an enquiry for your area.
            </p>
        </div>
    </section>

    <section class="locations-cta-section" aria-labelledby="locationsCtaTitle">
        <div class="container">
            <div class="locations-cta-box">
                <div>
                    <span class="section-eyebrow">Not Sure Which Page to Open?</span>
                    <h2 id="locationsCtaTitle">Tell Us Your Venue &amp; Date</h2>
                    <p>Share the event city, venue and date. Our team will confirm availability and guide you to the right decoration options.</p>
                </div>

                <div class="locations-cta-actions">
                    <a href="{{ url('/contact-us') }}" class="contact-primary-btn">
                        Send Enquiry
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>

                    @if (filled($profile->whatsappUrl()))
                        <a href="{{ $profile->whatsappUrl('Hello! I would like to check decoration availability in my area.') }}"
                           class="contact-outline-btn"
                           target="_blank"
                           rel="noopener">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp Enquiry
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
