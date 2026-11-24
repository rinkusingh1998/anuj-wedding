@extends('client.layout')

@section('content')
<main class="privacy-policy-page">
    <section class="contact-hero-section privacy-policy-hero-section"
        aria-labelledby="privacyPolicyHeroTitle">
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
                        Privacy Policy
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="privacyPolicyHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#privacyContent"
                       class="contact-primary-btn">
                        Read Privacy Policy
                        <i class="fa-solid fa-arrow-down"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="contact-outline-btn">
                        <i class="fa-solid fa-phone"></i>
                        Contact Us
                    </a>
                </div>
                <div class="contact-hero-trust">
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Privacy Focused
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Secure Enquiries
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Transparent Information
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="privacy-intro-section"
        id="privacyContent"
        aria-labelledby="privacyIntroTitle">
        <div class="container">
            <div class="privacy-intro-layout">
                <div class="privacy-intro-content">
                    <span class="privacy-eyebrow">
                        Privacy & Transparency
                    </span>
                    <h2 id="privacyIntroTitle">
                        Your Information,
                        <span>Your Trust</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_2') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_3') ?? '' }}
                    </p>
                </div>
                <div class="privacy-policy-card">
                    <div class="privacy-card-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="privacy-card-content">
                        <span>Our Commitment</span>
                        <strong>
                            Respecting Your Privacy
                        </strong>
                        <p>
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="privacy-content-section"
        aria-labelledby="privacyDetailsTitle">
        <div class="container">
            <div class="privacy-content-layout">
                <article class="privacy-article">
                    <div class="privacy-section">
                        <span class="privacy-number">01</span>
                        <div>
                            <h2 id="privacyDetailsTitle">
                                {{ $page->contentValue('copy.text_5') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_6') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_7') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">02</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_8') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_9') ?? '' }}
                            </p>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_10') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_11') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_12') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_13') ?? '' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">03</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_14') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_15') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_16') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">04</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_17') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_18') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_19') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">05</span>
                        <div>
                            <h2>
                                Website Security
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_20') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">06</span>
                        <div>
                            <h2>
                                Third-Party Websites
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_21') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_22') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">07</span>
                        <div>
                            <h2>
                                Cookies & Website Usage
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_23') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_24') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">08</span>
                        <div>
                            <h2>
                                Your Choices
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_25') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section">
                        <span class="privacy-number">09</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_26') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_27') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="privacy-section privacy-last-section">
                        <span class="privacy-number">10</span>
                        <div>
                            <h2>
                                Contact Us About Privacy
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_28') ?? '' }}
                            </p>
                            <a href="{{ url('/contact-us') }}"
                               class="privacy-contact-link">
                                Contact Our Team
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <aside class="privacy-sidebar">
                    <div class="privacy-sidebar-card">
                        <div class="privacy-sidebar-top">
                            <span class="privacy-sidebar-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <span>
                                Privacy Policy
                            </span>
                        </div>
                        <h3>
                            Important Information
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_29') ?? '' }}
                        </p>
                        <div class="privacy-sidebar-links">
                            <a href="#privacyContent">
                                <i class="fa-solid fa-chevron-right"></i>
                                Privacy Overview
                            </a>
                            <a href="#privacyDetailsTitle">
                                <i class="fa-solid fa-chevron-right"></i>
                                Information Collection
                            </a>
                            <a href="{{ url('/contact-us') }}">
                                <i class="fa-solid fa-chevron-right"></i>
                                Contact Us
                            </a>
                        </div>
                    </div>
                    <div class="privacy-help-card">
                        <span>
                            Need Help?
                        </span>
                        <h3>
                            Planning an Event?
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_30') ?? '' }}
                        </p>
                        <a href="{{ url('/contact-us') }}">
                            Discuss Your Event
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <section class="privacy-services-section"
        aria-labelledby="privacyServicesTitle">
        <div class="container">
            <div class="privacy-section-heading">
                <span class="privacy-eyebrow">
                    Explore Our Website
                </span>
                <h2 id="privacyServicesTitle">
                    Looking for Our
                    <span>Decoration Services?</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_31') ?? '' }}
                </p>
            </div>
            <div class="privacy-service-links">
                @foreach ($services->take(4) as $service)
                    <a href="{{ $service->url() }}">
                        <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                        <span>{{ $service->name }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <section class="privacy-final-section"
        aria-labelledby="privacyFinalTitle">
        <div class="container">
            <div class="privacy-final-inner">
                <div class="privacy-final-content">
                    <span class="privacy-eyebrow">
                        {{ $page->contentValue('copy.text_32') ?? '' }}
                    </span>
                    <h2 id="privacyFinalTitle">
                        Ready to Plan Your
                        <span>Special Celebration?</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_33') ?? '' }}
                    </p>
                </div>
                <div class="privacy-final-actions">
                    <a href="{{ url('/services') }}"
                       class="privacy-final-primary">
                        Explore Services
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="privacy-final-secondary">
                        Contact Us
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

