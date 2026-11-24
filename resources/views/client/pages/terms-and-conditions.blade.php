@extends('client.layout')

@section('content')
<main class="terms-page">
    <section class="contact-hero-section terms-hero-section"
        aria-labelledby="termsHeroTitle">
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
                        Terms & Conditions
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="termsHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#termsContent"
                       class="contact-primary-btn">
                        Read Terms
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
                        Clear Information
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Transparent Enquiries
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Responsible Service
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="terms-intro-section"
        id="termsContent"
        aria-labelledby="termsIntroTitle">
        <div class="container">
            <div class="terms-intro-layout">
                <div class="terms-intro-content">
                    <span class="terms-eyebrow">
                        Website Usage
                    </span>
                    <h2 id="termsIntroTitle">
                        Understanding Our
                        <span>Terms</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_2') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_3') ?? '' }}
                    </p>
                </div>
                <div class="terms-intro-card">
                    <div class="terms-card-icon">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <div class="terms-card-content">
                        <span>Important</span>
                        <strong>
                            Please Read Carefully
                        </strong>
                        <p>
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="terms-content-section"
        aria-labelledby="termsDetailsTitle">
        <div class="container">
            <div class="terms-content-layout">
                <article class="terms-article">
                    <div class="terms-section">
                        <span class="terms-number">01</span>
                        <div>
                            <h2 id="termsDetailsTitle">
                                Acceptance of Terms
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_5') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">02</span>
                        <div>
                            <h2>
                                Website Information
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_6') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_7') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">03</span>
                        <div>
                            <h2>
                                Service Enquiries
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_8') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_9') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">04</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_10') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_11') ?? '' }}
                            </p>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_12') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_13') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_14') ?? '' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_15') ?? '' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">05</span>
                        <div>
                            <h2>
                                Pricing & Quotations
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_16') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_17') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">06</span>
                        <div>
                            <h2>
                                Booking & Confirmation
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_18') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_19') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">07</span>
                        <div>
                            <h2>
                                Website Content & Images
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_20') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_21') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">08</span>
                        <div>
                            <h2>
                                Third-Party Links
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_22') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_23') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">09</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_24') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_25') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">10</span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_26') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_27') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section">
                        <span class="terms-number">11</span>
                        <div>
                            <h2>
                                Changes to These Terms
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_28') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_29') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="terms-section terms-last-section">
                        <span class="terms-number">12</span>
                        <div>
                            <h2>
                                Contact Us
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_30') ?? '' }}
                            </p>
                            <a href="{{ url('/contact-us') }}"
                               class="terms-contact-link">
                                Contact Our Team
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <aside class="terms-sidebar">
                    <div class="terms-sidebar-card">
                        <div class="terms-sidebar-top">
                            <span class="terms-sidebar-icon">
                                <i class="fa-solid fa-file-contract"></i>
                            </span>
                            <span>
                                Terms & Conditions
                            </span>
                        </div>
                        <h3>
                            Quick Navigation
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_31') ?? '' }}
                        </p>
                        <div class="terms-sidebar-links">
                            <a href="#termsContent">
                                <i class="fa-solid fa-chevron-right"></i>
                                Terms Overview
                            </a>
                            <a href="#termsDetailsTitle">
                                <i class="fa-solid fa-chevron-right"></i>
                                Website Usage
                            </a>
                            <a href="{{ url('/privacy-policy') }}">
                                <i class="fa-solid fa-chevron-right"></i>
                                Privacy Policy
                            </a>
                            <a href="{{ url('/contact-us') }}">
                                <i class="fa-solid fa-chevron-right"></i>
                                Contact Us
                            </a>
                        </div>
                    </div>
                    <div class="terms-help-card">
                        <span>
                            Need More Information?
                        </span>
                        <h3>
                            Let's Discuss Your Event
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_32') ?? '' }}
                        </p>
                        <a href="{{ url('/contact-us') }}">
                            Contact Our Team
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <section class="terms-related-section"
        aria-labelledby="termsRelatedTitle">
        <div class="container">
            <div class="terms-section-heading">
                <span class="terms-eyebrow">
                    Explore Our Website
                </span>
                <h2 id="termsRelatedTitle">
                    Helpful Pages For
                    <span>Your Event Planning</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_33') ?? '' }}
                </p>
            </div>
            <div class="terms-related-grid">
                <a href="{{ url('/services') }}"
                   class="terms-related-card">
                    <span>
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                    <div>
                        <h3>Our Services</h3>
                        <p>
                            {{ $page->contentValue('copy.text_34') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/packages') }}"
                   class="terms-related-card">
                    <span>
                        <i class="fa-solid fa-box-open"></i>
                    </span>
                    <div>
                        <h3>Decoration Packages</h3>
                        <p>
                            {{ $page->contentValue('copy.text_35') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/faq') }}"
                   class="terms-related-card">
                    <span>
                        <i class="fa-solid fa-circle-question"></i>
                    </span>
                    <div>
                        <h3>{{ $page->contentValue('copy.text_36') ?? '' }}</h3>
                        <p>
                            {{ $page->contentValue('copy.text_37') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ url('/privacy-policy') }}"
                   class="terms-related-card">
                    <span>
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <div>
                        <h3>Privacy Policy</h3>
                        <p>
                            {{ $page->contentValue('copy.text_38') ?? '' }}
                        </p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    <section class="terms-final-section"
        aria-labelledby="termsFinalTitle">
        <div class="container">
            <div class="terms-final-inner">
                <div class="terms-final-content">
                    <span class="terms-eyebrow">
                        {{ $page->contentValue('copy.text_39') ?? '' }}
                    </span>
                    <h2 id="termsFinalTitle">
                        Ready to Plan Your
                        <span>Special Celebration?</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_40') ?? '' }}
                    </p>
                </div>
                <div class="terms-final-actions">
                    <a href="{{ url('/services') }}"
                       class="terms-final-primary">
                        Explore Services
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="terms-final-secondary">
                        Contact Us
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

