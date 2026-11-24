@extends('client.layout')

@section('content')
<main class="faq-page">
    @php
    $faqGroups = collect($page->faqs ?? [])
        ->filter(fn ($faq) => is_array($faq) && filled($faq['question'] ?? null))
        ->groupBy(fn ($faq) => $faq['category'] ?? '');
    $groupFaqs = fn (string $key) => $faqGroups->get($key, collect())->values();
@endphp
<section class="contact-hero-section faq-hero-section"
    aria-labelledby="faqHeroTitle">
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
                    FAQ
                </span>
            </nav>
            <span class="contact-eyebrow">
                {{ $page->contentValue('copy.text_1') ?? '' }}
            </span>
            <h1 id="faqHeroTitle">
                {!! $seo['heading'] ?? '' !!}
            </h1>
            <p>
                {!! $seo['intro'] ?? '' !!}
            </p>
            <div class="contact-hero-actions">
                <a href="#faqList"
                   class="contact-primary-btn">
                    Explore FAQs
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
                    Wedding Decoration
                </span>
                <span>
                    <i class="fa-solid fa-check"></i>
                    Haldi & Mehendi Decoration
                </span>
                <span>
                    <i class="fa-solid fa-check"></i>
                    Customized Event Styling
                </span>
            </div>
        </div>
    </div>
</section>
    <section
        class="faq-category-section"
        aria-label="Frequently asked question categories">
        <div class="container">
            <div class="faq-category-grid">
                <a
                    href="#weddingFaq"
                    class="faq-category-card">
                    <span>
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <div>
                        <strong>
                            Wedding Decoration
                        </strong>
                        <small>
                            {{ $page->contentValue('copy.text_2') ?? '' }}
                        </small>
                    </div>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
                <a
                    href="#functionFaq"
                    class="faq-category-card">
                    <span>
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <div>
                        <strong>
                            Wedding Functions
                        </strong>
                        <small>
                            {{ $page->contentValue('copy.text_3') ?? '' }}
                        </small>
                    </div>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
                <a
                    href="#eventFaq"
                    class="faq-category-card">
                    <span>
                        <i class="fa-solid fa-cake-candles"></i>
                    </span>
                    <div>
                        <strong>
                            Parties & Events
                        </strong>
                        <small>
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                        </small>
                    </div>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
                <a
                    href="#bookingFaq"
                    class="faq-category-card">
                    <span>
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>
                    <div>
                        <strong>
                            Planning & Enquiry
                        </strong>
                        <small>
                            {{ $page->contentValue('copy.text_5') ?? '' }}
                        </small>
                    </div>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>
        </div>
    </section>
    <section
        class="faq-intro-section"
        aria-labelledby="faqIntroTitle">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="faq-intro-image">
                        <img
                            src="{{ $page->galleryImageAt(0) }}"
                            alt="Elegant wedding event decoration and venue styling"
                            loading="lazy"
                            decoding="async">
                        <div class="faq-intro-image-overlay">
                            <span>
                                EVENT DECORATION
                            </span>
                            <strong>
                                Planning Your
                                <br>
                                Perfect Celebration
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-intro-content">
                        <span class="faq-eyebrow">
                            BEFORE YOU PLAN
                        </span>
                        <h2 id="faqIntroTitle">
                            {{ $page->contentValue('copy.text_6') ?? '' }}
                            <span>Before Your Celebration</span>
                        </h2>
                        <p>
                            {{ $page->contentValue('copy.text_7') ?? '' }}
                        </p>
                        <p>
                            {{ $page->contentValue('copy.text_8') ?? '' }}
                        </p>
                        <div class="faq-intro-points">
                            <span>
                                <i class="fa-solid fa-check"></i>
                                {{ $page->contentValue('copy.text_9') ?? '' }}
                            </span>
                            <span>
                                <i class="fa-solid fa-check"></i>
                                {{ $page->contentValue('copy.text_10') ?? '' }}
                            </span>
                            <span>
                                <i class="fa-solid fa-check"></i>
                                {{ $page->contentValue('copy.text_11') ?? '' }}
                            </span>
                        </div>
                        <a
                            href="{{ url('/contact-us') }}"
                            class="faq-text-btn">
                            {{ $page->contentValue('copy.text_12') ?? '' }}
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-question-section faq-question-section-light"
        id="weddingFaq"
        aria-labelledby="weddingFaqTitle">
        <div class="container">
            <div class="faq-section-heading">
                <span class="faq-eyebrow">
                    WEDDING DECORATION
                </span>
                <h2 id="weddingFaqTitle">
                    Wedding Decoration
                    <span>{{ $page->contentValue('copy.text_13') ?? '' }}</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_14') ?? '' }}
                </p>
            </div>
            <div class="faq-layout">
                <div class="faq-question-image">
                    <img
                        src="{{ $page->galleryImageAt(1) }}"
                        alt="Elegant wedding decoration and wedding venue styling"
                        loading="lazy"
                        decoding="async">
                    <div class="faq-image-label">
                        WEDDING DECOR
                    </div>
                </div>
                <div class="faq-accordion">
                    <div
                        class="accordion"
                        id="weddingFaqAccordion">
                        @foreach ($groupFaqs('wedding') as $index => $faq)
                            <div class="accordion-item faq-item">
                                <h3
                                    class="accordion-header"
                                    id="weddingQuestion{{ $index }}">
                                    <button
                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#weddingAnswer{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="weddingAnswer{{ $index }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h3>
                                <div
                                    id="weddingAnswer{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="weddingQuestion{{ $index }}"
                                    data-bs-parent="#weddingFaqAccordion">
                                    <div class="accordion-body">
                                        {!! $faq['answer'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-question-section"
        id="functionFaq"
        aria-labelledby="functionFaqTitle">
        <div class="container">
            <div class="faq-section-heading">
                <span class="faq-eyebrow">
                    WEDDING FUNCTIONS
                </span>
                <h2 id="functionFaqTitle">
                    {{ $page->contentValue('copy.text_15') ?? '' }}
                    <span>Decoration Questions</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_16') ?? '' }}
                </p>
            </div>
            <div class="faq-layout faq-layout-reverse">
                <div class="faq-accordion">
                    <div
                        class="accordion"
                        id="functionFaqAccordion">
                        @foreach ($groupFaqs('function') as $index => $faq)
                            <div class="accordion-item faq-item">
                                <h3
                                    class="accordion-header"
                                    id="functionQuestion{{ $index }}">
                                    <button
                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#functionAnswer{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="functionAnswer{{ $index }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h3>
                                <div
                                    id="functionAnswer{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="functionQuestion{{ $index }}"
                                    data-bs-parent="#functionFaqAccordion">
                                    <div class="accordion-body">
                                        {!! $faq['answer'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="faq-question-image">
                    <img
                        src="{{ $page->galleryImageAt(2) }}"
                        alt="Colourful wedding function decoration setup"
                        loading="lazy"
                        decoding="async">
                    <div class="faq-image-label">
                        WEDDING FUNCTIONS
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-question-section faq-question-section-light"
        id="eventFaq"
        aria-labelledby="eventFaqTitle">
        <div class="container">
            <div class="faq-section-heading">
                <span class="faq-eyebrow">
                    PARTIES & EVENTS
                </span>
                <h2 id="eventFaqTitle">
                    {{ $page->contentValue('copy.text_17') ?? '' }}
                    <span>Questions Answered</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_18') ?? '' }}
                </p>
            </div>
            <div class="faq-layout">
                <div class="faq-question-image">
                    <img
                        src="{{ $page->galleryImageAt(3) }}"
                        alt="Colourful birthday balloon decoration for a celebration"
                        loading="lazy"
                        decoding="async">
                    <div class="faq-image-label">
                        PARTY DECORATION
                    </div>
                </div>
                <div class="faq-accordion">
                    <div
                        class="accordion"
                        id="eventFaqAccordion">
                        @foreach ($groupFaqs('event') as $index => $faq)
                            <div class="accordion-item faq-item">
                                <h3
                                    class="accordion-header"
                                    id="eventQuestion{{ $index }}">
                                    <button
                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#eventAnswer{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="eventAnswer{{ $index }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h3>
                                <div
                                    id="eventAnswer{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="eventQuestion{{ $index }}"
                                    data-bs-parent="#eventFaqAccordion">
                                    <div class="accordion-body">
                                        {!! $faq['answer'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-question-section"
        id="bookingFaq"
        aria-labelledby="bookingFaqTitle">
        <div class="container">
            <div class="faq-section-heading">
                <span class="faq-eyebrow">
                    PLANNING & ENQUIRY
                </span>
                <h2 id="bookingFaqTitle">
                    Planning Your Event
                    <span>Questions & Answers</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_19') ?? '' }}
                </p>
            </div>
            <div class="faq-planning-grid">
                <div class="faq-planning-image">
                    <img
                        src="{{ $page->galleryImageAt(4) }}"
                        alt="Professionally decorated event venue ready for celebration"
                        loading="lazy"
                        decoding="async">
                    <div class="faq-planning-overlay">
                        <span>
                            PLAN WITH CONFIDENCE
                        </span>
                        <strong>
                            Your Event.
                            <br>
                            Your Style.
                            <br>
                            Your Celebration.
                        </strong>
                    </div>
                </div>
                <div class="faq-planning-accordion">
                    <div
                        class="accordion"
                        id="bookingFaqAccordion">
                        @foreach ($groupFaqs('booking') as $index => $faq)
                            <div class="accordion-item faq-item">
                                <h3
                                    class="accordion-header"
                                    id="bookingQuestion{{ $index }}">
                                    <button
                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#bookingAnswer{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="bookingAnswer{{ $index }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h3>
                                <div
                                    id="bookingAnswer{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="bookingQuestion{{ $index }}"
                                    data-bs-parent="#bookingFaqAccordion">
                                    <div class="accordion-body">
                                        {!! $faq['answer'] !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-services-section"
        aria-labelledby="faqServicesTitle">
        <div class="container">
            <div class="faq-section-heading">
                <span class="faq-eyebrow">
                    EXPLORE OUR SERVICES
                </span>
                <h2 id="faqServicesTitle">
                    Looking For A Specific
                    <span>Decoration Service?</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_20') ?? '' }}
                </p>
            </div>
                        <div class="faq-services-grid">
                @foreach ($services->take(4) as $service)
                    <a
                        href="{{ $service->url() }}"
                        class="faq-service-card">
                        <div class="faq-service-image">
                            <img
                                src="{{ $service->imageUrl() }}"
                                alt="{{ $service->name }} service"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <div class="faq-service-content">
                            <small>
                                {{ strtoupper($service->category?->name ?: 'Event Decor') }}
                            </small>
                            <strong>
                                {{ $service->name }}
                            </strong>
                            <span>
                                {{ $service->shortDescription() }}
                            </span>
                            <em>
                                View Service
                                <i class="fa-solid fa-arrow-right"></i>
                            </em>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <section
        class="faq-location-section"
        aria-labelledby="faqLocationTitle">
        <div class="container"><div class="faq-location-box">
                <div class="faq-location-content">
                    @if (filled(site_label('service_areas')))
                        <h2 id="faqLocationTitle">{{ site_label('service_areas') }}</h2>
                    @endif
                    @if (filled(site_setting('service_areas_intro')))
                        <p>{{ site_setting('service_areas_intro') }}</p>
                    @endif
                    <a
                        href="{{ url('/contact-us') }}"
                        class="faq-location-btn">
                        {{ site_label('contact_our_team') }}
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="faq-location-image">
                    <img
                        src="{{ $page->galleryImageAt(9) }}"
                        alt="{{ $page->name }}"
                        loading="lazy"
                        decoding="async">
                </div>
                <div class="faq-location-list">
                    @foreach (collect($visibleStates ?? []) as $areaState)
                        @foreach ($areaState['cities'] as $areaCity)
                            <a href="{{ $areaCity['url'] }}">{{ $areaCity['name'] }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section
        class="faq-final-section"
        aria-labelledby="faqFinalTitle">
        <div class="container">
            <div class="faq-final-box">
                <div class="faq-final-image">
                    <img
                        src="{{ $page->galleryImageAt(10) }}"
                        alt=""
                        aria-hidden="true"
                        loading="lazy"
                        decoding="async">
                </div>
                <div class="faq-final-overlay"></div>
                <div class="faq-final-content">
                    <span class="faq-eyebrow">
                        STILL HAVE A QUESTION?
                    </span>
                    <h2 id="faqFinalTitle">
                        Let's Talk About
                        <span>Your Celebration</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_25') ?? '' }}
                    </p>
                </div>
                <div class="faq-final-actions">
                    <a
                        href="{{ url('/contact-us') }}"
                        class="faq-final-primary">
                        Contact Us
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a
                        href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                        class="faq-final-phone">
                        <i class="fa-solid fa-phone"></i>
                        {{ $profile->phone() }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

