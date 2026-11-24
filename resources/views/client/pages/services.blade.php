@extends('client.layout')

@section('content')
<main class="all-services-page">
    <section class="contact-hero-section all-services-hero-section" aria-labelledby="allServicesHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">Services</span>
                </nav>

                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? 'Wedding & Event Decoration Services' }}
                </span>

                <h1 id="allServicesHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>

                <p>{!! $seo['intro'] ?? '' !!}</p>

                <div class="contact-hero-actions">
                    <a href="#allServicesList" class="contact-primary-btn">
                        Explore Our Services
                        <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}" class="contact-outline-btn">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Plan Your Event
                    </a>
                </div>

                <div class="contact-hero-trust">
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Wedding Decoration</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Function &amp; Party Decoration</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Custom Event Styling</span>
                </div>
            </div>
        </div>
    </section>

    <section class="all-services-list-section" id="allServicesList" aria-labelledby="allServicesListTitle">
        <div class="container">
            <div class="all-services-heading all-services-heading-center">
                <span class="all-services-eyebrow">Our Services</span>
                <h2 id="allServicesListTitle">
                    Explore All Our
                    <span>Decoration Services</span>
                </h2>
                <p>{{ $page->contentValue('copy.text_6') ?? '' }}</p>
            </div>

            <div class="all-services-grid">
                <div class="row g-4">
                    @forelse ($services as $service)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ $service->url() }}"
                               class="service-premium-card"
                               aria-label="Explore {{ $service->name }}">
                                <div class="service-card-image">
                                    <img src="{{ $service->imageUrl() }}"
                                         alt="{{ $service->name }} in Uttar Pradesh"
                                         loading="lazy">
                                    <span class="service-card-number">
                                        {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                                <div class="service-card-body">
                                    <span class="service-card-icon">
                                        <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}" aria-hidden="true"></i>
                                    </span>
                                    <span class="service-card-category">
                                        {{ strtoupper($service->short_name ?: $service->name) }}
                                    </span>
                                    <h3>{{ $service->name }}</h3>
                                    <p>{{ $service->shortDescription() }}</p>
                                    <span class="service-card-link">
                                        Explore Service
                                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p>{{ site_label('content_services_empty') ?? '' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="service-experience-section" aria-labelledby="serviceExperienceTitle">
        <div class="container">
            <div class="service-experience-box">
                <div class="service-experience-content">
                    <span class="all-services-eyebrow">More Than Decoration</span>
                    <h2 id="serviceExperienceTitle">
                        We Design The
                        <span>{{ $page->contentValue('copy.text_7') ?? 'Complete Celebration' }}</span>
                    </h2>
                    <p>{{ $page->contentValue('copy.text_8') ?? '' }}</p>

                    <div class="service-experience-points">
                        <div><i class="fa-solid fa-check" aria-hidden="true"></i><span>Theme-focused decoration</span></div>
                        <div><i class="fa-solid fa-check" aria-hidden="true"></i><span>Venue-specific planning</span></div>
                        <div><i class="fa-solid fa-check" aria-hidden="true"></i><span>Professional event setup</span></div>
                        <div><i class="fa-solid fa-check" aria-hidden="true"></i><span>{{ $page->contentValue('copy.text_9') ?? '' }}</span></div>
                    </div>
                </div>

                <div class="service-experience-mark">
                    <span>AK</span>
                    <small>WEDDING<br>EVENT<br>PLANNERS</small>
                </div>
            </div>
        </div>
    </section>

    <x-service-areas />

    <section class="all-services-final-section" aria-labelledby="servicesFinalTitle">
        <div class="container">
            <div class="all-services-final-box">
                <div class="all-services-final-content">
                    <span class="all-services-eyebrow">Plan Your Celebration</span>
                    <h2 id="servicesFinalTitle">
                        Found The Right
                        <span>Decoration Style?</span>
                    </h2>
                    <p>{{ $page->contentValue('copy.text_10') ?? '' }}</p>
                </div>

                <div class="all-services-final-actions">
                    <a href="{{ url('/contact-us') }}" class="all-services-primary-btn">
                        Send An Enquiry
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>

                    @if (filled($profile->whatsappUrl()))
                        <a href="{{ $profile->whatsappUrl('Hello! I would like to discuss decoration services for my event.') }}"
                           class="all-services-phone-btn"
                           target="_blank"
                           rel="noopener">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp
                        </a>
                    @endif

                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}" class="all-services-phone-btn">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        {{ $profile->phone() }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
