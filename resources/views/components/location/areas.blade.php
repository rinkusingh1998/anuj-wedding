{{-- =========================================================
     AREA LINKS (nearby cities / all cities)
     Internal linking ka sabse bada source — isi se saare
     city pages Google me discover hote hain.
========================================================= --}}

@props([
    'cities',
    'tag' => null,
    'title' => null,
    'text' => null,
    'soft' => false,
])

@if ($cities->isNotEmpty())

    <section class="loc-section {{ $soft ? 'loc-section-soft' : '' }}" aria-labelledby="locAreasTitle">

        <div class="container">

            <div class="loc-section-head">

                @if (filled($tag))
                    <span class="loc-section-tag">{{ $tag }}</span>
                @endif

                @if (filled($title))
                    <h2 id="locAreasTitle">{{ $title }}</h2>
                @endif

                @if ($text)
                    <p>{{ $text }}</p>
                @endif

            </div>

            <div class="loc-areas justify-content-center d-flex">

                @foreach ($cities as $city)

                    <a href="{{ $city->url() }}" class="loc-area-link">

                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>

                        {{ $city->name }}

                    </a>

                @endforeach

            </div>

        </div>

    </section>

@endif
