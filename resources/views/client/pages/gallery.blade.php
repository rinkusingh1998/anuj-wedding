@extends('client.layout')

@section('content')
<main class="gallery-page">
    <section class="contact-hero-section gallery-hero-section" aria-labelledby="galleryHeroTitle">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">Gallery</span>
                </nav>

                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? 'Wedding & Event Decoration Gallery' }}
                </span>

                <h1 id="galleryHeroTitle">{!! $seo['heading'] ?? '' !!}</h1>

                @if (filled($seo['intro'] ?? null))
                    <p>{!! $seo['intro'] !!}</p>
                @endif

                <div class="contact-hero-actions">
                    <a href="#galleryCollection" class="contact-primary-btn">
                        Explore Gallery
                        <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}" class="contact-outline-btn">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        Plan Your Event
                    </a>
                </div>

                <div class="contact-hero-trust">
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Elegant Decoration</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Beautiful Event Setups</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Wedding &amp; Event Coverage</span>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-collection-section" id="galleryCollection" aria-labelledby="galleryCollectionTitle">
        <div class="container">
            <div class="gallery-section-heading gallery-heading-center">
                <span class="gallery-eyebrow">Event Decoration Collection</span>
                <h2 id="galleryCollectionTitle">
                    Explore Our Decoration
                    <span>Ideas &amp; Setups</span>
                </h2>
                <p>{{ $page->contentValue('copy.text_4') ?? '' }}</p>
            </div>

            @if (collect($galleryItems)->isNotEmpty())
                <div class="premium-gallery-grid">
                    @foreach ($galleryItems as $item)
                        <figure class="premium-gallery-card {{ $loop->index % 5 === 0 ? 'is-tall' : '' }}">
                            <div class="gallery-image">
                                <img src="{{ $item['image'] }}"
                                     alt="{{ $item['title'] }} by {{ $profile->name() }}"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                            <figcaption>
                                <span>{{ strtoupper($item['category']) }}</span>
                                <h3>{{ $item['title'] }}</h3>
                                <p>Beautifully styled {{ strtolower($item['title']) }} for celebrations across Uttar Pradesh.</p>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @else
                <p>{{ site_label('content_gallery_empty') ?? '' }}</p>
            @endif
        </div>
    </section>

    <section class="gallery-highlight-section" aria-labelledby="galleryHighlightTitle">
        <div class="container">
            <div class="gallery-highlight-box">
                <div class="gallery-highlight-content">
                    <span class="gallery-eyebrow">Your Vision • Our Decoration</span>
                    <h2 id="galleryHighlightTitle">
                        Your Event Deserves
                        <span>{{ $page->contentValue('copy.text_5') ?? 'A Beautiful Setting' }}</span>
                    </h2>
                    <p>{{ $page->contentValue('copy.text_6') ?? '' }}</p>
                    <a href="{{ url('/contact-us') }}" class="gallery-dark-btn">
                        <span>Discuss Your Decoration</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="gallery-highlight-mark">
                    <span>AK</span>
                    <small>EVENT<br>DECORATION</small>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-services-section" aria-labelledby="galleryServicesTitle">
        <div class="container">
            <div class="gallery-section-heading gallery-heading-center">
                <span class="gallery-eyebrow">Our Decoration Services</span>
                <h2 id="galleryServicesTitle">
                    Decoration Services
                    <span>For Every Celebration</span>
                </h2>
                <p>{{ $page->contentValue('copy.text_7') ?? '' }}</p>
            </div>

            <div class="gallery-service-grid">
                <div class="row g-4">
                    @foreach ($services->take(8) as $service)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ $service->url() }}" class="gallery-service-card">
                                <span class="gallery-service-icon">
                                    <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}" aria-hidden="true"></i>
                                </span>
                                <span class="gallery-service-content">
                                    <strong>{{ $service->name }}</strong>
                                    <small>{{ $service->category?->name ?: 'Decoration service' }}</small>
                                </span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-final-section" aria-labelledby="galleryFinalTitle">
        <div class="container">
            <div class="gallery-final-box">
                <div class="gallery-final-content">
                    <span class="gallery-eyebrow">{{ $page->contentValue('copy.text_8') ?? 'Plan With Us' }}</span>
                    <h2 id="galleryFinalTitle">
                        Planning Your Next
                        <span>Special Celebration?</span>
                    </h2>
                    <p>{{ $page->contentValue('copy.text_9') ?? '' }}</p>
                </div>

                <div class="gallery-final-actions">
                    <a href="{{ url('/contact-us') }}" class="gallery-final-primary">
                        <span>Send An Enquiry</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>

                    @if (filled($profile->whatsappUrl()))
                        <a href="{{ $profile->whatsappUrl('Hello! I saw your decoration gallery and would like to discuss my event.') }}"
                           class="gallery-final-phone"
                           target="_blank"
                           rel="noopener"
                           onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            <span>WhatsApp Us</span>
                        </a>
                    @endif

                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}" class="gallery-final-phone">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <span>{{ $profile->phone() }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
