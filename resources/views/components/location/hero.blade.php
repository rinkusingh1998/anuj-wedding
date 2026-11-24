{{-- Location hero: breadcrumb + H1 + intro + CTAs. Every string comes from the database. --}}

@props([
    'seo',
    'profile',
    'image' => null,
    'title' => null,
    'whatsappMessage' => null,
])

@php
    $heading = filled($seo['heading'] ?? null) ? $seo['heading'] : $title;
    $phone = $profile->phone();
    $whatsapp = $profile->whatsappUrl($whatsappMessage);
@endphp

<section
    class="loc-hero"
    aria-labelledby="locHeroTitle"
    @if ($image)
        style="background-image: linear-gradient(105deg, rgba(30, 16, 10, 0.92) 0%, rgba(52, 26, 16, 0.82) 55%, rgba(88, 38, 24, 0.72) 100%), url('{{ $image }}'); background-size: cover; background-position: center;"
    @endif
>

    <div class="container">

        <div class="loc-hero-inner">

            @if (! empty($seo['breadcrumbs']))
                <nav class="loc-breadcrumb" aria-label="Breadcrumb">
                    @foreach ($seo['breadcrumbs'] as $crumb)
                        @if (! $loop->first)
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        @endif

                        @if ($loop->last)
                            <span aria-current="page">{{ $crumb['name'] }}</span>
                        @else
                            <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if (filled($seo['eyebrow'] ?? null))
                <span class="loc-eyebrow">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    {{ $seo['eyebrow'] }}
                </span>
            @endif

            @if (filled($heading))
                <h1 id="locHeroTitle">
                    {{ $heading }}

                    @if (filled($seo['sub_heading'] ?? null))
                        <span>{{ $seo['sub_heading'] }}</span>
                    @endif
                </h1>
            @endif

            @if (filled($seo['intro'] ?? null))
                <div class="loc-hero-intro">
                    {!! $seo['intro'] !!}
                </div>
            @endif

            <div class="loc-hero-actions">

                @if (filled($phone))
                    <a href="tel:+{{ preg_replace('/\D/', '', $phone) }}"
                       class="loc-btn loc-btn-primary"
                       data-cta="contact">

                        <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>

                        Call: {{ $phone }}

                    </a>
                @endif

                @if (filled($whatsapp))
                    <a href="{{ $whatsapp }}"
                       class="loc-btn loc-btn-whatsapp"
                       target="_blank"
                       rel="noopener">

                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>

                        Chat on WhatsApp

                    </a>
                @endif

            </div>

            @if (! empty($seo['highlights']))
                <div class="loc-chips">
                    @foreach (array_slice($seo['highlights'], 0, 5) as $highlight)
                        <span class="loc-chip">
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                            {{ $highlight }}
                        </span>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

</section>
