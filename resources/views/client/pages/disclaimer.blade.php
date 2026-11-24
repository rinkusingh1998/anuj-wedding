@extends('client.layout')

@section('content')
<main class="disclaimer-page">
    <section class="contact-hero-section disclaimer-hero-section"
        aria-labelledby="disclaimerHeroTitle">
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
                        Disclaimer
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="disclaimerHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#disclaimerContent"
                       class="contact-primary-btn">
                        Read Disclaimer
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
                        General Information
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        {{ $page->contentValue('copy.text_2') ?? '' }}
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Service Information
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="disclaimer-intro-section"
        id="disclaimerContent"
        aria-labelledby="disclaimerIntroTitle">
        <div class="container">
            <div class="disclaimer-intro-layout">
                <div class="disclaimer-intro-content">
                    <span class="disclaimer-eyebrow">
                        Important Notice
                    </span>
                    <h2 id="disclaimerIntroTitle">
                        Information Provided
                        <span>For General Purposes</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_3') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_4') ?? '' }}
                    </p>
                </div>
                <div class="disclaimer-intro-card">
                    <div class="disclaimer-card-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="disclaimer-card-content">
                        <span>Please Note</span>
                        <strong>
                            {{ $page->contentValue('copy.text_5') ?? '' }}
                        </strong>
                        <p>
                            {{ $page->contentValue('copy.text_6') ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="disclaimer-content-section"
        aria-labelledby="disclaimerDetailsTitle">
        <div class="container">
            <div class="disclaimer-content-layout">
                <article class="disclaimer-article">
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            01
                        </span>
                        <div>
                            <h2 id="disclaimerDetailsTitle">
                                General Information
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_7') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_8') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            02
                        </span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_9') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_10') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_11') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            03
                        </span>
                        <div>
                            <h2>
                                Service Availability
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_12') ?? '' }}
                            </p>
                            <ul>
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
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    {{ $page->contentValue('copy.text_16') ?? '' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            04
                        </span>
                        <div>
                            <h2>
                                Pricing & Quotations
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_17') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_18') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            05
                        </span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_19') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_20') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_21') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            06
                        </span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_22') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_23') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_24') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            07
                        </span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_25') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_26') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_27') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            08
                        </span>
                        <div>
                            <h2>
                                {{ $page->contentValue('copy.text_28') ?? '' }}
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_29') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_30') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section">
                        <span class="disclaimer-number">
                            09
                        </span>
                        <div>
                            <h2>
                                Website Availability
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_31') ?? '' }}
                            </p>
                            <p>
                                {{ $page->contentValue('copy.text_32') ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="disclaimer-section disclaimer-last-section">
                        <span class="disclaimer-number">
                            10
                        </span>
                        <div>
                            <h2>
                                Contact & Clarification
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_33') ?? '' }}
                            </p>
                            <a href="{{ url('/contact-us') }}"
                               class="disclaimer-contact-link">
                                Contact Our Team
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <aside class="disclaimer-sidebar">
                    <div class="disclaimer-sidebar-card">
                        <div class="disclaimer-sidebar-top">
                            <span class="disclaimer-sidebar-icon">
                                <i class="fa-solid fa-circle-info"></i>
                            </span>
                            <span>
                                Disclaimer
                            </span>
                        </div>
                        <h3>
                            Quick Information
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_34') ?? '' }}
                        </p>
                        <div class="disclaimer-sidebar-links">
                            <a href="#disclaimerContent">
                                <i class="fa-solid fa-chevron-right"></i>
                                Disclaimer Overview
                            </a>
                            <a href="#disclaimerDetailsTitle">
                                <i class="fa-solid fa-chevron-right"></i>
                                Website Information
                            </a>
                            <a href="{{ url('/terms-and-conditions') }}">
                                <i class="fa-solid fa-chevron-right"></i>
                                Terms & Conditions
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
                    <div class="disclaimer-help-card">
                        <span>
                            Need Confirmation?
                        </span>
                        <h3>
                            Discuss Your Event
                        </h3>
                        <p>
                            {{ $page->contentValue('copy.text_35') ?? '' }}
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
    <section class="disclaimer-services-section"
        aria-labelledby="disclaimerServicesTitle">
        <div class="container">
            <div class="disclaimer-section-heading">
                <span class="disclaimer-eyebrow">
                    Explore Our Services
                </span>
                <h2 id="disclaimerServicesTitle">
                    Planning a Celebration?
                    <span>Explore Your Options</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_36') ?? '' }}
                </p>
            </div>
            <div class="disclaimer-service-links">
                @foreach ($services->take(4) as $service)
                    <a href="{{ $service->url() }}">
                        <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                        <span>
                            {{ $service->name }}
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <section class="disclaimer-final-section"
        aria-labelledby="disclaimerFinalTitle">
        <div class="container">
            <div class="disclaimer-final-inner">
                <div class="disclaimer-final-content">
                    <span class="disclaimer-eyebrow">
                        {{ $page->contentValue('copy.text_37') ?? '' }}
                    </span>
                    <h2 id="disclaimerFinalTitle">
                        Have Questions About
                        <span>Your Event?</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_38') ?? '' }}
                    </p>
                </div>
                <div class="disclaimer-final-actions">
                    <a href="{{ url('/services') }}"
                       class="disclaimer-final-primary">
                        Explore Services
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="disclaimer-final-secondary">
                        Contact Us
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

