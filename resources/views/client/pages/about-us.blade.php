@extends('client.layout')

@section('content')
<main class="about-page">
    <section class="about-hero-section" aria-labelledby="aboutHeroTitle">
        <div class="about-hero-overlay"></div>
        <div class="container">
            <div class="about-hero-content">
                <nav class="about-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">
                        Home
                    </a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">
                        About Us
                    </span>
                </nav>
                <span class="about-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="aboutHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="about-hero-actions">
                    <a href="{{ url('/contact-us') }}"
                       class="about-primary-btn">
                        Plan Your Celebration
                        <i class="fa-solid fa-arrow-right"
                           aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('/services') }}"
                       class="about-outline-btn">
                        Explore Our Services
                        <i class="fa-solid fa-arrow-right"
                           aria-hidden="true"></i>
                    </a>
                </div>
                <div class="about-hero-trust">
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Wedding Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Event Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Customized Themes
                    </span>
                </div>
            </div>
        </div>
        <div class="about-hero-scroll" aria-hidden="true">
            <span>
                Discover Our Story
            </span>
            <i class="fa-solid fa-arrow-down"></i>
        </div>
    </section>
    <section class="about-introduction-section"
             aria-labelledby="aboutStoryTitle">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-story-visual">
                        <div class="about-story-main-image">
                            <img
                                src="{{ $page->galleryImageAt(0) }}"
                                alt="Elegant wedding decoration setup by Anuj Kumar Wedding Event Planners"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <div class="about-story-small-image">
                            <img
                                src="{{ $page->galleryImageAt(1) }}"
                                alt="Beautiful wedding decoration and floral decoration setup"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <div class="about-story-experience">
                            <strong>
                                5<sup>+</sup>
                            </strong>
                            <span>
                                {{ $page->contentValue('copy.text_2') ?? '' }}
                            </span>
                        </div>
                        <div class="about-story-seal">
                            <i class="fa-solid fa-heart"></i>
                            <span>
                                Designed With Care
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-story-content">
                        <span class="about-section-eyebrow">
                            {{ $page->contentValue('copy.text_3') ?? '' }}
                        </span>
                        <h2 id="aboutStoryTitle">
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                            <span>Beautiful Experiences</span>
                        </h2>
                        <div class="about-heading-line"></div>
                        @php
    $aboutStory = $page->contentValue('about_story', '');
@endphp
                        @if (filled($aboutStory))
                            {!! str_replace('{address}', e($profile->address()), $aboutStory) !!}
                        @endif
                        <div class="about-story-highlights">
                            <div class="about-story-highlight">
                                <div class="about-story-highlight-icon">
                                    <i class="fa-solid fa-palette"></i>
                                </div>
                                <div>
                                    <strong>
                                        Creative Decoration Concepts
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_5') ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="about-story-highlight">
                                <div class="about-story-highlight-icon">
                                    <i class="fa-solid fa-sliders"></i>
                                </div>
                                <div>
                                    <strong>
                                        Customized Styling
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_6') ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="about-story-highlight">
                                <div class="about-story-highlight-icon">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                                <div>
                                    <strong>
                                        Organized Execution
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_7') ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="about-story-highlight">
                                <div class="about-story-highlight-icon">
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                                <div>
                                    <strong>
                                        Personal Attention
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_8') ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url('/contact-us') }}"
                           class="about-text-link">
                            {{ $page->contentValue('copy.text_9') ?? '' }}
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-values-section"
             aria-labelledby="aboutValuesTitle">
        <div class="container">
            <div class="about-section-heading">
                <span class="about-section-eyebrow">
                    {{ $page->contentValue('copy.text_10') ?? '' }}
                </span>
                <h2 id="aboutValuesTitle">
                    Thoughtful Planning,
                    <span>Beautiful Decoration</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_11') ?? '' }}
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <article class="about-value-card">
                        <span class="about-value-number">
                            01
                        </span>
                        <div class="about-value-icon">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <h3>
                            {{ $page->contentValue('copy.text_12') ?? '' }}
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_13') ?? '' }}
                        </p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6">
                    <article class="about-value-card">
                        <span class="about-value-number">
                            02
                        </span>
                        <div class="about-value-icon">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <h3>
                            Personalized Themes
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_14') ?? '' }}
                        </p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6">
                    <article class="about-value-card">
                        <span class="about-value-number">
                            03
                        </span>
                        <div class="about-value-icon">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <h3>
                            Organized Planning
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_15') ?? '' }}
                        </p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6">
                    <article class="about-value-card">
                        <span class="about-value-number">
                            04
                        </span>
                        <div class="about-value-icon">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <h3>
                            {{ $page->contentValue('copy.text_16') ?? '' }}
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_17') ?? '' }}
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
    <section class="about-trust-section"
             aria-labelledby="aboutTrustTitle">
        <div class="container">
            <div class="about-trust-wrapper">
                <div class="about-trust-content">
                    <span class="about-section-eyebrow">
                        {{ $page->contentValue('copy.text_18') ?? '' }}
                    </span>
                    <h2 id="aboutTrustTitle">
                        {{ $page->contentValue('copy.text_19') ?? '' }}
                        <span>Something Special</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_20') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_21') ?? '' }}
                    </p>
                    <div class="about-trust-points">
                        <span>
                            <i class="fa-solid fa-check"></i>
                            Theme-Based Decoration
                        </span>
                        <span>
                            <i class="fa-solid fa-check"></i>
                            Venue-Focused Planning
                        </span>
                        <span>
                            <i class="fa-solid fa-check"></i>
                            {{ $page->contentValue('copy.text_22') ?? '' }}
                        </span>
                        <span>
                            <i class="fa-solid fa-check"></i>
                            {{ $page->contentValue('copy.text_23') ?? '' }}
                        </span>
                    </div>
                </div>
                <div class="about-trust-visual">
                    <div class="about-trust-image">
                        <img
                            src="{{ $page->galleryImageAt(2) }}"
                            alt="Premium wedding venue decoration and event styling"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                    <div class="about-trust-card">
                        <i class="fa-solid fa-star"></i>
                        <strong>
                            {{ $page->contentValue('copy.text_24') ?? '' }}
                        </strong>
                        <span>
                            {{ $page->contentValue('copy.text_25') ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-services-section"
             aria-labelledby="aboutServicesTitle">
        <div class="container">
            <div class="about-section-heading">
                <span class="about-section-eyebrow">
                    {{ $page->contentValue('copy.text_26') ?? '' }}
                </span>
                <h2 id="aboutServicesTitle">
                    Decoration Services For
                    <span>Every Celebration</span>
                </h2>
                @if (filled($page->intro_content))
                    <p>{!! $page->intro_content !!}</p>
                @endif
            </div>
                        <div class="row g-4">
                @foreach ($services as $service)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <article class="about-service-card">
                            <a href="{{ $service->url() }}"
                               class="about-service-image"
                               aria-label="{{ $service->name }} service">
                                <img
                                    src="{{ $service->imageUrl() }}"
                                    alt="{{ $service->name }} decoration"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <span class="about-service-icon">
                                    <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                                </span>
                            </a>
                            <div class="about-service-content">
                                <span class="about-service-label">
                                    {{ strtoupper($service->category?->name ?: 'Decoration Service') }}
                                </span>
                                <h3>
                                    <a href="{{ $service->url() }}">
                                        {{ $service->name }}
                                    </a>
                                </h3>
                                <p>
                                    {{ $service->shortDescription() }}
                                </p>
                                <a href="{{ $service->url() }}"
                                   class="about-service-link">
                                    Explore Service
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div><div class="about-services-footer">
                <p>
                    {{ $page->contentValue('copy.text_36') ?? '' }}
                </p>
                <a href="{{ url('/contact-us') }}"
                   class="about-text-link">
                    Tell Us About Your Event
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    <section class="about-process-section"
             aria-labelledby="aboutProcessTitle">
        <div class="container">
            <div class="about-section-heading">
                <span class="about-section-eyebrow">
                    {{ $page->contentValue('copy.text_37') ?? '' }}
                </span>
                <h2 id="aboutProcessTitle">
                    From Your Vision
                    <span>To The Final Setup</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_38') ?? '' }}
                </p>
            </div>
            <div class="about-process-grid">
                <div class="about-process-line"></div>
                <article class="about-process-card">
                    <span class="about-process-number">
                        01
                    </span>
                    <div class="about-process-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3>
                        Understand
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_39') ?? '' }}
                    </p>
                </article>
                <article class="about-process-card">
                    <span class="about-process-number">
                        02
                    </span>
                    <div class="about-process-icon">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </div>
                    <h3>
                        Plan
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_40') ?? '' }}
                    </p>
                </article>
                <article class="about-process-card">
                    <span class="about-process-number">
                        03
                    </span>
                    <div class="about-process-icon">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3>
                        Decorate
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_41') ?? '' }}
                    </p>
                </article>
                <article class="about-process-card">
                    <span class="about-process-number">
                        04
                    </span>
                    <div class="about-process-icon">
                        <i class="fa-solid fa-champagne-glasses"></i>
                    </div>
                    <h3>
                        Celebrate
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_42') ?? '' }}
                    </p>
                </article>
            </div>
        </div>
    </section>
    <section class="about-final-cta-section"
             aria-labelledby="aboutFinalCtaTitle">
        <div class="container">
            <div class="about-final-cta">
                <div class="about-final-content">
                    <span class="about-final-eyebrow">
                        {{ $page->contentValue('copy.text_43') ?? '' }}
                    </span>
                    <h2 id="aboutFinalCtaTitle">
                        {{ $page->contentValue('copy.text_44') ?? '' }}
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_45') ?? '' }}
                    </p>
                    <div class="about-final-actions">
                        <a href="{{ url('/contact-us') }}"
                           class="about-primary-btn">
                            Send An Enquiry
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                           class="about-final-phone">
                            <span>
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <div>
                                <small>
                                    Call For Your Event
                                </small>
                                <strong>
                                    {{ $profile->phone() }}
                                </strong>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="about-final-decoration"
                     aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                    <i class="fa-regular fa-heart"></i>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

