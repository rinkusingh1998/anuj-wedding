@extends('client.layout')

{{--
=========================================================================
 SERVICE LANDING PAGE  ( /services/{slug} )
 Example: /services/wedding-decoration, /services/haldi-decoration
 Content is managed from the admin panel (SEO & Locations > Services).
=========================================================================
--}}

@push('head_links')
    <link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}?v=20261125">
@endpush

@section('content')

@php
    $landing = $service->contentValue('landing', []);
    $themes = collect($landing['themes'] ?? [])->filter()->values();
    $setupAreas = collect($landing['setup_areas'] ?? [])->filter()->values();
    $whyChoose = collect($landing['why_choose'] ?? [])->filter()->values();
    $venues = collect($landing['venues'] ?? [])->filter()->values();
    $heroHighlights = collect($landing['hero_highlights'] ?? ($seo['highlights'] ?? []))->filter()->take(4);
    $galleryImages = $service->galleryImages(12);
    $coverage = $cities->count();
    $whatsappMessage = 'Hello! I am interested in '.$service->name.'. Please share the design options, availability and pricing.';
    $serviceWhatsApp = $profile->whatsappUrl($whatsappMessage);
    $heroHeading = filled($seo['heading'] ?? null) ? $seo['heading'] : $service->name.' in Uttar Pradesh';
    $detailHeading = $landing['detail_heading'] ?? $service->name.' Decoration Service';
    $relatedServices = $services
        ->filter(fn ($item): bool => (int) $item->getKey() !== (int) $service->getKey())
        ->take(4)
        ->values();
@endphp

<main class="loc-page premium-service-page">

    <section class="svc-hero"
        aria-labelledby="svcHeroTitle"
        style="background-image: linear-gradient(105deg, rgba(25, 14, 9, 0.94) 0%, rgba(50, 25, 15, 0.84) 56%, rgba(92, 42, 24, 0.66) 100%), url('{{ $service->imageUrl() }}');">

        <div class="container">
            <div class="svc-hero-inner">
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

                <span class="svc-eyebrow">
                    <i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i>
                    {{ $seo['eyebrow'] ?? 'Wedding & Event Decoration' }}
                </span>

                <h1 id="svcHeroTitle">{{ $heroHeading }}</h1>

                @if (filled($seo['sub_heading'] ?? null))
                    <p class="svc-subheading">{{ $seo['sub_heading'] }}</p>
                @endif

                @if (filled($seo['intro'] ?? null))
                    <div class="svc-hero-intro">{!! $seo['intro'] !!}</div>
                @endif

                @if ($heroHighlights->isNotEmpty())
                    <div class="svc-hero-chips">
                        @foreach ($heroHighlights as $highlight)
                            <span>
                                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                                {{ $highlight }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="svc-hero-actions">
                    <a href="#serviceEnquiry" class="loc-btn loc-btn-primary">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Get a Free Quote
                    </a>

                    @if (filled($serviceWhatsApp))
                        <a href="{{ $serviceWhatsApp }}" class="loc-btn loc-btn-whatsapp" target="_blank" rel="noopener" onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp Enquiry
                        </a>
                    @endif

                    @if (filled($profile->phone()))
                        <a href="tel:+{{ preg_replace('/\D/', '', $profile->phone()) }}" class="loc-btn loc-btn-outline">
                            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
                            Call Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="loc-section svc-main-section" aria-labelledby="svcDetailTitle">
        <div class="container">
            <div class="svc-layout">
                <aside class="svc-media">
                    <img src="{{ $service->imageUrl() }}"
                         alt="{{ $service->image_alt ?: $service->name.' decoration in Uttar Pradesh' }}"
                         loading="eager"
                         decoding="async">

                    <div class="svc-media-card">
                        <span class="loc-section-tag">{{ site_label('service_detail') ?: 'Service Detail' }}</span>
                        <ul>
                            <li>
                                <span>Category</span>
                                <strong>{{ $service->category?->name ?: 'Wedding Decoration' }}</strong>
                            </li>
                            <li>
                                <span>Coverage</span>
                                <strong>{{ $coverage }} published locations</strong>
                            </li>
                            @if (filled($service->tagline))
                                <li>
                                    <span>Best for</span>
                                    <strong>{{ $service->tagline }}</strong>
                                </li>
                            @endif
                        </ul>

                        @if (filled($serviceWhatsApp))
                            <a href="{{ $serviceWhatsApp }}" target="_blank" rel="noopener" class="svc-media-whatsapp" onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                Ask for availability
                            </a>
                        @endif
                    </div>
                </aside>

                <div class="svc-content">
                    <span class="loc-section-tag">Complete Service Guide</span>
                    <h2 id="svcDetailTitle">{{ $detailHeading }}</h2>

                    @if (filled($service->tagline))
                        <p class="svc-lead">{{ $service->tagline }}</p>
                    @endif

                    <div class="svc-richtext">
                        {!! $service->long_description ?: $seo['about'] !!}
                    </div>

                    @if (! empty($seo['highlights']))
                        <div class="svc-highlight-list">
                            @foreach (array_slice($seo['highlights'], 0, 8) as $highlight)
                                <span>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    {{ $highlight }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="svc-inline-actions">
                        <a href="{{ url('contact-us') }}" class="svc-btn svc-btn-primary">
                            Send Your Requirements
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>

                        @if (filled($serviceWhatsApp))
                            <a href="{{ $serviceWhatsApp }}" class="svc-btn svc-btn-ghost" target="_blank" rel="noopener" onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                Chat With Our Team
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-content-sections
        :sections="$service->contentValue('sections', [])"
        :label="$service->name.' content'"
    />

    @if ($themes->isNotEmpty())
        <section class="loc-section svc-section-soft" aria-labelledby="svcThemesTitle">
            <div class="container">
                <div class="loc-section-head">
                    <span class="loc-section-tag">Themes &amp; Concepts</span>
                    <h2 id="svcThemesTitle">Choose a Look That Feels Like You</h2>
                    <p>Explore the themes we most often build for {{ $service->name }}. Every setup is adapted to your venue, colours, family traditions and photography plan.</p>
                </div>

                <div class="svc-card-grid">
                    @foreach ($themes as $theme)
                        <article class="svc-card">
                            <span class="svc-card-icon">
                                <i class="{{ $theme['icon'] ?? 'fa-solid fa-star' }}" aria-hidden="true"></i>
                            </span>
                            <h3>{{ $theme['title'] ?? 'Decoration theme' }}</h3>
                            @if (filled($theme['text'] ?? null))
                                <p>{{ $theme['text'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($setupAreas->isNotEmpty())
        <section class="loc-section" aria-labelledby="svcSetupTitle">
            <div class="container">
                <div class="loc-section-head">
                    <span class="loc-section-tag">What We Decorate</span>
                    <h2 id="svcSetupTitle">Every Important Corner, Thoughtfully Styled</h2>
                    <p>From the first entrance moment to the final photograph, our team plans each setup as part of one complete visual story.</p>
                </div>

                <div class="svc-area-grid">
                    @foreach ($setupAreas as $area)
                        <article class="svc-area-card">
                            <div class="svc-area-head">
                                <span>
                                    <i class="{{ $area['icon'] ?? 'fa-solid fa-wand-magic-sparkles' }}" aria-hidden="true"></i>
                                </span>
                                <h3>{{ $area['title'] ?? 'Decoration setup' }}</h3>
                            </div>
                            @if (filled($area['text'] ?? null))
                                <p>{{ $area['text'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($venues->isNotEmpty())
        <section class="loc-section svc-section-soft" aria-labelledby="svcVenueTitle">
            <div class="container">
                <div class="loc-section-head">
                    <span class="loc-section-tag">Venue Planning</span>
                    <h2 id="svcVenueTitle">Decoration That Works With Your Venue</h2>
                    <p>We plan according to ceiling height, entrance width, natural light, guest movement and the venue's decoration rules.</p>
                </div>

                <div class="svc-venue-grid">
                    @foreach ($venues as $venue)
                        <article class="svc-venue-card">
                            <h3>{{ $venue['title'] ?? 'Venue type' }}</h3>
                            @if (filled($venue['text'] ?? null))
                                <p>{{ $venue['text'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-location.gallery
        :images="$galleryImages"
        tag="Service Gallery"
        :title="'Recent '.$service->name.' Setups'"
        :alt="$service->name.' decoration by '.$profile->name()"
    />

    @if ($whyChoose->isNotEmpty())
        <section class="loc-section svc-why-section" aria-labelledby="svcWhyTitle">
            <div class="container">
                <div class="svc-why-layout">
                    <div class="loc-section-head">
                        <span class="loc-section-tag">Why Choose Us</span>
                        <h2 id="svcWhyTitle">A Calm, Organised Decoration Experience</h2>
                        <p>Good decoration is not only about how it looks. It is also about timely setup, clear communication and a team that understands your family's priorities.</p>
                    </div>

                    <div class="svc-why-grid">
                        @foreach ($whyChoose as $reason)
                            <article class="svc-why-card">
                                <i class="{{ $reason['icon'] ?? 'fa-solid fa-check' }}" aria-hidden="true"></i>
                                <div>
                                    <h3>{{ $reason['title'] ?? 'Why choose us' }}</h3>
                                    @if (filled($reason['text'] ?? null))
                                        <p>{{ $reason['text'] }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($relatedServices->isNotEmpty())
        <section class="loc-section svc-section-soft" aria-labelledby="svcRelatedTitle">
            <div class="container">
                <div class="loc-section-head">
                    <span class="loc-section-tag">Related Services</span>
                    <h2 id="svcRelatedTitle">Complete Your Celebration With</h2>
                    <p>Many families combine more than one function. Choose related decoration services and we will coordinate the complete schedule together.</p>
                </div>

                <div class="svc-related-grid">
                    @foreach ($relatedServices as $related)
                        <a href="{{ $related->url() }}" class="svc-related-card">
                            <div class="svc-related-image">
                                <img src="{{ $related->imageUrl() }}"
                                     alt="{{ $related->name }} decoration"
                                     loading="lazy">
                            </div>
                            <div class="svc-related-body">
                                <span>{{ $related->short_name ?: $related->name }}</span>
                                <h3>{{ $related->name }}</h3>
                                <p>{{ $related->shortDescription(20) }}</p>
                                <strong>
                                    Explore Service
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </strong>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-service-areas
        :title="'Our Service Areas for '.$service->name"
        :text="'We accept '.$service->name.' bookings across Uttar Pradesh and nearby cities. Open your location page for local details, service links and booking information.'"
    />

    <x-location.faqs
        :faqs="$seo['faqs']"
        tag="Quick Answers"
        :title="$service->name.' — Frequently Asked Questions'"
    />

    <section class="loc-cta svc-final-cta" id="serviceEnquiry" aria-labelledby="svcFinalTitle">
        <div class="container">
            <div class="loc-cta-inner">
                <div>
                    <span class="loc-section-tag">Start Planning</span>
                    <h2 id="svcFinalTitle">Book {{ $service->name }} for Your Date</h2>
                    <p>Share your date, city, venue and budget. Our team will send design options, photographs and a clear quotation — usually the same day.</p>
                </div>

                <div class="loc-cta-actions">
                    <a href="{{ url('contact-us') }}" class="loc-btn loc-btn-primary">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        Send Enquiry
                    </a>

                    @if (filled($serviceWhatsApp))
                        <a href="{{ $serviceWhatsApp }}" class="loc-btn loc-btn-whatsapp" target="_blank" rel="noopener" onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp Now
                        </a>
                    @endif

                    @if (filled($profile->phone()))
                        <a href="tel:+{{ preg_replace('/\D/', '', $profile->phone()) }}" class="loc-btn loc-btn-outline">
                            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
                            {{ $profile->phone() }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

</main>

@endsection
