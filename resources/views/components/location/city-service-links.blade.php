{{-- =========================================================
     CITY-WISE SERVICE LINKS
     Ek service ke saare "{state}/{city}/{service}" pages ke links.
     Yahi internal linking Google ko saare city x service pages
     jaldi discover karwati hai.
========================================================= --}}

@props([
    'service',
    'cities',
    'tag' => null,
    'title' => null,
    'text' => null,
])

@if ($cities->isNotEmpty())

    <section class="loc-section loc-section-soft" aria-labelledby="locServiceCityTitle">

        <div class="container">

            <div class="loc-section-head">

                @if (filled($tag))
                    <span class="loc-section-tag">{{ $tag }}</span>
                @endif

                <h2 id="locServiceCityTitle">
                    {{ $title ?? $service->name.' in Your City' }}
                </h2>

                @if ($text)
                    <p>{{ $text }}</p>
                @endif

            </div>

            <div class="loc-areas justify-content-center d-flex">

                @foreach ($cities as $city)

                    <a href="{{ $city->serviceUrl($service) }}" class="loc-area-link">
                        {{ $service->short_name ?: $service->name }} in {{ $city->name }}
                    </a>

                @endforeach

            </div>

        </div>

    </section>

@endif
