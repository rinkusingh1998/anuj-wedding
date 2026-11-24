@extends('client.layout')

@section('content')
<main class="contact-page">
    <section class="contact-hero-section" aria-labelledby="contactHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">
                        Home
                    </a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">
                        Contact Us
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="contactHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#contactEnquiry"
                       class="contact-primary-btn">
                        Send An Enquiry
                        <i class="fa-solid fa-arrow-down"></i>
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                       class="contact-outline-btn">
                        <i class="fa-solid fa-phone"></i>
                        {{ $profile->phone() }}
                    </a>
                </div>
                <div class="contact-hero-trust">
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Quick Enquiry
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Customized Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Nearby Event Coverage
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-info-section"
             aria-label="Contact information">
        <div class="container">
            <div class="contact-info-grid">
                <article class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="contact-info-content">
                        <span>
                            CALL US
                        </span>
                        <h2>
                            Speak With Our Team
                        </h2>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}">
                            {{ $profile->phone() }}
                        </a>
                        <p>
                            {{ $page->contentValue('copy.text_2') ?? '' }}
                        </p>
                    </div>
                </article>
                <article class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="contact-info-content">
                        <span>
                            WHATSAPP
                        </span>
                        <h2>
                            Send Your Requirement
                        </h2>
                        <a href="{{ $profile->whatsappUrl() }}"
                           class="contact-whatsapp-link whatsapp-desktop d-lg-block d-md-block d-none"
                           target="_blank"
                           rel="noopener noreferrer">
                            Chat On WhatsApp
                        </a>
                        <a href="{{ $profile->whatsappUrl() }}"
                           class="contact-whatsapp-link whatsapp-mobile d-lg-none d-md-none d-block"
                           target="_blank"
                           rel="noopener noreferrer">
                            Chat On WhatsApp
                        </a>
                        <p>
                            {{ $page->contentValue('copy.text_3') ?? '' }}
                        </p>
                    </div>
                </article>
                <article class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="contact-info-content">
                        <span>
                            VISIT / LOCATION
                        </span>
                        <h2>
                            Our Location
                        </h2>
                        <a href="{{ $profile->mapUrl() }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            View On Google Maps
                        </a>
                        <p>
                            {{ $profile->address() }}
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>
    <section class="contact-enquiry-section"
             id="contactEnquiry"
             aria-labelledby="contactEnquiryTitle">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-lg-5">
                    <div class="contact-enquiry-content">
                        <span class="contact-section-eyebrow">
                            Plan Your Event With Us
                        </span>
                        <h2 id="contactEnquiryTitle">
                            Tell Us About
                            <span>Your Celebration</span>
                        </h2>
                        <p>
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                        </p>
                        <p>
                            {{ $page->contentValue('copy.text_5') ?? '' }}
                        </p>
                        <div class="contact-enquiry-benefits">
                            <div class="contact-benefit">
                                <div class="contact-benefit-icon">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                                <div>
                                    <strong>
                                        Share Your Event Details
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_6') ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="contact-benefit">
                                <div class="contact-benefit-icon">
                                    <i class="fa-solid fa-palette"></i>
                                </div>
                                <div>
                                    <strong>
                                        Choose Your Decoration
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_7') ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="contact-benefit">
                                <div class="contact-benefit-icon">
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                                <div>
                                    <strong>
                                        {{ $page->contentValue('copy.text_8') ?? '' }}
                                    </strong>
                                    <span>
                                        {{ $page->contentValue('copy.text_9') ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="contact-direct-box">
                            <div class="contact-direct-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span>
                                    Prefer To Talk Directly?
                                </span>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}">
                                    {{ $profile->phone() }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <div class="contact-form-header">
                            <span>
                                EVENT ENQUIRY
                            </span>
                            <h2>
                                Let's Discuss Your Event
                            </h2>
                            <p>
                                {{ $page->contentValue('copy.text_10') ?? '' }}
                            </p>
                        </div>
                        <x-enquiry-form
                            id-prefix="contact"
                            source-page="contact-us"
                            variant="contact"
                            form-class="contact-premium-form"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-services-section"
             aria-labelledby="contactServicesTitle">
        <div class="container">
            <div class="contact-section-heading">
                <span class="contact-section-eyebrow">
                    {{ $page->contentValue('copy.text_12') ?? '' }}
                </span>
                <h2 id="contactServicesTitle">
                    Explore Our
                    <span>Decoration Services</span>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_13') ?? '' }}
                </p>
            </div>
                        <div class="contact-services-grid">
                @foreach ($services as $service)
                    <a href="{{ $service->url() }}"
                       class="contact-service-link-card">
                        <span class="contact-service-link-icon">
                            <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                        </span>
                        <div>
                            <strong>
                                {{ $service->name }}
                            </strong>
                            <small>
                                {{ $service->shortDescription() }}
                            </small>
                        </div>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section><section class="contact-location-section"
             aria-labelledby="contactLocationTitle">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="contact-location-content">
                        @if (filled(site_label('service_areas')))
                            <span class="contact-section-eyebrow">{{ site_label('service_areas') }}</span>
                            <h2 id="contactLocationTitle">{{ site_label('service_areas') }}</h2>
                        @endif
                        @if (filled(site_setting('service_areas_intro')))
                            <p>{{ site_setting('service_areas_intro') }}</p>
                        @endif
                        <div class="contact-location-address">
                            <div class="contact-location-address-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span>
                                    OUR LOCATION
                                </span>
                                <strong>
                                    {{ $profile->address() }}
                                </strong>
                            </div>
                        </div>
                        <a
                            href="{{ $profile->mapUrl() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="contact-map-btn"
                        >
                            {{ site_label('open_google_maps') }}
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-location-panel">
                        <div class="contact-location-panel-head">
                            <div>
                                <span>
                                    {{ site_label('service_areas') }}
                                </span>
                                <h3>
                                    {{ site_label('nearby_cities') }}
                                </h3>
                            </div>
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="contact-location-list">
                            @foreach (collect($visibleStates ?? []) as $areaState)
                                @foreach ($areaState['cities'] as $areaCity)
                                    <a href="{{ $areaCity['url'] }}">
                                        <i class="fa-solid fa-check"></i>
                                        {{ $areaCity['name'] }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                        @if (filled(site_setting('service_areas_note')))
                            <div class="contact-location-note">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>{{ site_setting('service_areas_note') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-final-section">
        <div class="container">
            <div class="contact-final-box">
                <div class="contact-final-content">
                    <span>
                        HAVE AN EVENT IN MIND?
                    </span>
                    <h2>
                        {{ $page->contentValue('copy.text_21') ?? '' }}
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_22') ?? '' }}
                    </p>
                    <div class="contact-final-actions">
                        <a href="#contactEnquiry"
                           class="contact-primary-btn">
                            Start Your Enquiry
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                           class="contact-final-phone">
                            <i class="fa-solid fa-phone"></i>
                            <span>
                                {{ $profile->phone() }}
                            </span>
                        </a>
                    </div>
                </div>
                <div class="contact-final-decoration"
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

