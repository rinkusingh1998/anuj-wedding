@props([
    'title' => null,
    'text' => null,
])

@php
    $states = collect($visibleStates ?? []);
    $heading = $title ?: (site_label('service_areas') ?: 'Our Service Areas');
    $intro = $text ?: site_setting('service_areas_intro');
    $note = site_setting('service_areas_note');
    $totalCities = $states->sum(fn (array $state): int => count($state['cities'] ?? []));
@endphp

@if ($states->isNotEmpty())
    <section class="sa-section all-services-location-section" aria-labelledby="serviceAreasTitle">
        <div class="container">
            <div class="sa-head">
                <span class="sa-eyebrow">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    Local Coverage
                </span>

                <h2 id="serviceAreasTitle">{{ $heading }}</h2>

                @if (filled($intro))
                    <p>{{ $intro }}</p>
                @endif

                <div class="sa-meta" aria-label="Service coverage summary">
                    <span>
                        <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                        {{ $totalCities }} published locations
                    </span>
                    <span>
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Advance booking recommended
                    </span>
                    <span>
                        <i class="fa-solid fa-car-side" aria-hidden="true"></i>
                        Nearby &amp; outstation on request
                    </span>
                </div>

                @if ($totalCities > 12)
                    <div class="sa-filter">
                        <label class="visually-hidden" for="serviceAreaSearch">Search service areas</label>
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input
                            id="serviceAreaSearch"
                            type="search"
                            placeholder="Search your city or area"
                            autocomplete="off"
                            data-area-filter
                        >
                        <span data-area-count>{{ $totalCities }} areas</span>
                    </div>
                @endif
            </div>

            @foreach ($states as $state)
                <div class="sa-state">
                    <h3>
                        <a href="{{ $state['url'] }}">{{ $state['name'] }}</a>
                    </h3>

                    <div class="sa-grid" data-area-grid>
                        @foreach ($state['cities'] as $city)
                            <a href="{{ $city['url'] }}"
                               class="sa-card"
                               data-area-name="{{ strtolower($city['name'].' '.($city['district'] ?? '')) }}">
                                <span class="sa-card-icon">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                </span>
                                <span class="sa-card-copy">
                                    <strong>{{ $city['name'] }}</strong>
                                    @if (filled($city['district'] ?? null) && $city['district'] !== $city['name'])
                                        <small>{{ $city['district'] }}</small>
                                    @endif
                                </span>
                                <i class="fa-solid fa-arrow-right sa-card-arrow" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if (filled($note))
                <p class="sa-note">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                    {{ $note }}
                </p>
            @endif
        </div>
    </section>
@endif
