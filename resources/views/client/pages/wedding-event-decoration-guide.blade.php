@extends('client.layout')

@section('content')
<main class="decoration-guide-page">
    <section class="contact-hero-section decoration-guide-hero"
        aria-labelledby="decorationGuideHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">
                        Home
                    </a>
                    <i class="fa-solid fa-chevron-right"
                       aria-hidden="true"></i>
                    <span aria-current="page">
                        Decoration Guide
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="decorationGuideHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#guideServices"
                       class="contact-primary-btn">
                        Explore Decoration Ideas
                        <i class="fa-solid fa-arrow-down"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="contact-outline-btn">
                        <i class="fa-solid fa-calendar-check"></i>
                        Plan Your Event
                    </a>
                </div>
                <div class="contact-hero-trust">
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Wedding Decoration Ideas
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Function Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Event Planning Tips
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="guide-intro-section"
        aria-labelledby="guideIntroTitle">
        <div class="container">
            <div class="guide-intro-grid">
                <div class="guide-intro-content">
                    <span class="guide-eyebrow">
                        {{ $page->contentValue('copy.text_2') ?? '' }}
                    </span>
                    <h2 id="guideIntroTitle">
                        {{ $page->contentValue('copy.text_3') ?? '' }}
                        <span>Good Planning</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_4') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_5') ?? '' }}
                    </p>
                </div>
                <div class="guide-intro-card">
                    <span class="guide-card-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </span>
                    <div>
                        <strong>
                            Planning Tip
                        </strong>
                        <p>
                            {{ $page->contentValue('copy.text_6') ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="guide-quick-section"
        aria-labelledby="guideQuickTitle">
        <div class="container">
            <div class="guide-heading">
                <span class="guide-eyebrow">
                    Start Here
                </span>
                <h2 id="guideQuickTitle">
                    What Are You Planning?
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_7') ?? '' }}
                </p>
            </div>
            <div class="guide-quick-grid">
                <a href="#weddingDecoration"
                   class="guide-quick-card">
                    <span>
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <div>
                        <strong>Wedding</strong>
                        <small>{{ site_label('content_guide_wedding_text') ?? '' }}</small>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#haldiDecoration"
                   class="guide-quick-card">
                    <span>
                        <i class="fa-solid fa-sun"></i>
                    </span>
                    <div>
                        <strong>Haldi</strong>
                        <small>{{ site_label('content_guide_haldi_text') ?? '' }}</small>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#mehendiDecoration"
                   class="guide-quick-card">
                    <span>
                        <i class="fa-solid fa-leaf"></i>
                    </span>
                    <div>
                        <strong>Mehendi</strong>
                        <small>{{ site_label('content_guide_mehendi_text') ?? '' }}</small>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#partyDecoration"
                   class="guide-quick-card">
                    <span>
                        <i class="fa-solid fa-cake-candles"></i>
                    </span>
                    <div>
                        <strong>Party & Events</strong>
                        <small>{{ site_label('content_guide_balloon_text') ?? '' }}</small>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    <section class="guide-services-section"
        id="guideServices"
        aria-labelledby="guideServicesTitle">
        <div class="container">
            <div class="guide-heading">
                <span class="guide-eyebrow">
                    Decoration Services
                </span>
                <h2 id="guideServicesTitle">
                    {{ $page->contentValue('copy.text_8') ?? '' }}
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_9') ?? '' }}
                </p>
            </div>
                        <div class="guide-services-grid">
                @php
                    $guideAnchors = [
                        'wedding-decoration' => 'weddingDecoration',
                        'haldi-decoration' => 'haldiDecoration',
                        'mehendi-decoration' => 'mehendiDecoration',
                        'complete-event-decor' => 'partyDecoration',
                    ];
                @endphp

                @foreach ($services->take(8) as $service)
                    <a href="{{ $service->url() }}"
                       class="guide-service-card"
                       @if (isset($guideAnchors[$service->slug])) id="{{ $guideAnchors[$service->slug] }}" @endif>
                        <span class="guide-service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="guide-service-icon">
                            <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                        </div>
                        <h3>
                            {{ $service->name }}
                        </h3>
                        <p>
                            {{ $service->shortDescription() }}
                        </p>
                        <span class="guide-service-link">
                            Explore Service
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section><section class="guide-checklist-section"
        aria-labelledby="guideChecklistTitle">
        <div class="container">
            <div class="guide-checklist-layout">
                <div class="guide-checklist-content">
                    <span class="guide-eyebrow">
                        Event Planning Checklist
                    </span>
                    <h2 id="guideChecklistTitle">
                        7 Things To Decide
                        <span>Before Decoration</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_19') ?? '' }}
                    </p>
                </div>
                <div class="guide-checklist-list">
                    <div>
                        <span>01</span>
                        <strong>Event Type</strong>
                        <p>{{ site_label('content_guide_before_event') ?? '' }}</p>
                    </div>
                    <div>
                        <span>02</span>
                        <strong>Venue</strong>
                        <p>{{ site_label('content_guide_venue_space') ?? '' }}</p>
                    </div>
                    <div>
                        <span>03</span>
                        <strong>Theme</strong>
                        <p>{{ site_label('content_guide_theme') ?? '' }}</p>
                    </div>
                    <div>
                        <span>04</span>
                        <strong>Colours</strong>
                        <p>{{ site_label('content_guide_colours') ?? '' }}</p>
                    </div>
                    <div>
                        <span>05</span>
                        <strong>Stage & Backdrop</strong>
                        <p>{{ site_label('content_guide_stage') ?? '' }}</p>
                    </div>
                    <div>
                        <span>06</span>
                        <strong>Entrance & Details</strong>
                        <p>{{ site_label('content_guide_entry') ?? '' }}</p>
                    </div>
                    <div>
                        <span>07</span>
                        <strong>Event Date</strong>
                        <p>{{ site_label('content_guide_booking') ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="guide-choose-section"
        aria-labelledby="guideChooseTitle">
        <div class="container">
            <div class="guide-heading">
                <span class="guide-eyebrow">
                    {{ $page->contentValue('copy.text_20') ?? '' }}
                </span>
                <h2 id="guideChooseTitle">
                    {{ $page->contentValue('copy.text_21') ?? '' }}
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_22') ?? '' }}
                </p>
            </div>
            <div class="guide-choose-grid">
                <article>
                    <span>
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <h3>
                        Start With The Venue
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_23') ?? '' }}
                    </p>
                </article>
                <article>
                    <span>
                        <i class="fa-solid fa-palette"></i>
                    </span>
                    <h3>
                        Build A Colour Story
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_24') ?? '' }}
                    </p>
                </article>
                <article>
                    <span>
                        <i class="fa-solid fa-camera"></i>
                    </span>
                    <h3>
                        Create A Focal Point
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_25') ?? '' }}
                    </p>
                </article>
                <article>
                    <span>
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    <h3>
                        Plan Early
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_26') ?? '' }}
                    </p>
                </article>
            </div>
        </div>
    </section>
    <section class="guide-local-section"
        aria-labelledby="guideLocalTitle">
        <div class="container">
            <div class="guide-local-layout">
                <div class="guide-local-content">
                    @if (filled(site_label('service_areas')))
                        <span class="guide-eyebrow">{{ site_label('service_areas') }}</span>
                        <h2 id="guideLocalTitle">{{ site_label('service_areas') }}</h2>
                    @endif
                    @if (filled(site_setting('service_areas_intro')))
                        <p>{{ site_setting('service_areas_intro') }}</p>
                    @endif
                    <a href="{{ url('/contact-us') }}"
                       class="guide-local-btn">
                        {{ site_label('discuss_your_event') }}
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="guide-local-areas">
                    @foreach (collect($visibleStates ?? []) as $areaState)
                        @foreach ($areaState['cities'] as $areaCity)
                            <a href="{{ $areaCity['url'] }}">{{ $areaCity['name'] }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section class="guide-resources-section"
        aria-labelledby="guideResourcesTitle">
        <div class="container">
            <div class="guide-heading">
                <span class="guide-eyebrow">
                    Continue Planning
                </span>
                <h2 id="guideResourcesTitle">
                    Helpful Resources
                    <span>For Your Celebration</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_27') ?? '' }}
                </p>
            </div>
            <div class="guide-resources-grid">
                <a href="{{ url('/gallery') }}"
                   class="guide-resource-card">
                    <span>
                        <i class="fa-solid fa-images"></i>
                    </span>
                    <div>
                        <h3>Decoration Gallery</h3>
                        <p>
                            {{ $page->contentValue('copy.text_28') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/packages') }}"
                   class="guide-resource-card">
                    <span>
                        <i class="fa-solid fa-box-open"></i>
                    </span>
                    <div>
                        <h3>Decoration Packages</h3>
                        <p>
                            {{ $page->contentValue('copy.text_29') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/blog') }}"
                   class="guide-resource-card">
                    <span>
                        <i class="fa-solid fa-pen-nib"></i>
                    </span>
                    <div>
                        <h3>Decoration Blog</h3>
                        <p>
                            {{ $page->contentValue('copy.text_30') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/faq') }}"
                   class="guide-resource-card">
                    <span>
                        <i class="fa-solid fa-circle-question"></i>
                    </span>
                    <div>
                        <h3>FAQs</h3>
                        <p>
                            {{ $page->contentValue('copy.text_31') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    <section class="guide-final-section"
        aria-labelledby="guideFinalTitle">
        <div class="container">
            <div class="guide-final-inner">
                <div>
                    <span class="guide-eyebrow">
                        {{ $page->contentValue('copy.text_32') ?? '' }}
                    </span>
                    <h2 id="guideFinalTitle">
                        {{ $page->contentValue('copy.text_33') ?? '' }}
                        <span>Let's Discuss It.</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_34') ?? '' }}
                    </p>
                </div>
                <div class="guide-final-actions">
                    <a href="{{ url('/contact-us') }}"
                       class="guide-final-primary">
                        Plan Your Event
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/services') }}"
                       class="guide-final-secondary">
                        Explore Services
                        <i class="fa-solid fa-layer-group"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

