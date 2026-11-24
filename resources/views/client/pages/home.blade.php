@extends('client.layout')

{{-- rinku singh --}}

@php
    $content = $page->content ?? [];
    $slides = collect($content['hero_slides'] ?? [])
        ->filter(fn ($slide): bool => is_array($slide) && ($slide['is_active'] ?? true))
        ->values()
        ->all();
    $trustItems = $content['trust_items'] ?? [];
    $about = $content['about'] ?? [];
    $servicesHeading = $content['services_heading'] ?? [];
    $servicesCta = $content['services_cta'] ?? [];
    $galleryHeading = $content['gallery_heading'] ?? [];
    $process = $content['process'] ?? [];
    $packagesHeading = $content['packages'] ?? [];
    $stats = $content['stats'] ?? [];
    $testimonialsSection = $content['testimonials'] ?? [];
    $testimonialItems = $testimonialsSection['items'] ?? [];
    if (empty($testimonialItems) && isset($testimonials) && $testimonials instanceof \Illuminate\Support\Collection) {
        $testimonialItems = $testimonials->map(fn ($testimonial) => [
            'quote' => $testimonial->quote,
            'name' => $testimonial->name,
            'event' => $testimonial->event,
            'image' => $testimonial->imageUrl(),
        ])->all();
    }
    $blogsHeading = $content['blogs'] ?? [];
    $contact = $content['contact'] ?? [];
    $faqs = $page->faqs ?: ($content['faqs'] ?? []);
    $galleryItems = $page->galleryItems(12);
    $phone = $profile->phone();
    $tel = preg_replace('/\D/', '', $phone);
@endphp
@section('content')
<main class="home-page">
<!-- start hero slider section -->
@if (! empty($slides))
<section class="hero-slider-section" id="hero-slider">
    <div id="heroSlider"
         class="carousel slide carousel-fade"
         data-bs-ride="carousel"
         data-bs-interval="5500">
        <!-- start slider items -->
        <div class="carousel-inner">
            @foreach ($slides as $slide)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="hero-slide"
                         style="background-image:url('{{ $page->mediaUrl($slide['image'] ?? null) ?? $page->imageUrl() }}');">
                        <div class="hero-overlay"></div>
                        <div class="container">
                            <div class="hero-content">
                                @if (! empty($slide['tag']))
                                    <span class="hero-tag">{{ $slide['tag'] }}</span>
                                @endif
                                @if ($loop->first)
                                    <h1>{!! $slide['heading'] !!}</h1>
                                @else
                                    <h2>{!! $slide['heading'] !!}</h2>
                                @endif
                                <p>{!! $slide['description'] !!}</p>
                                <div class="hero-buttons">
                                    <a href="{{ $slide['primary_url'] ?? '' }}" class="hero-btn hero-btn-primary">
                                        {{ $slide['primary_label'] ?? '' }}
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <a href="tel:+{{ $tel }}" class="hero-btn hero-btn-outline">
                                        <i class="fa-solid fa-phone"></i>
                                        {{ site_label('talk_to_us') ?? '' }}
                                    </a>
                                </div>
                                @if (! empty($slide['highlights']))
                                    <div class="hero-list">
                                        @foreach ($slide['highlights'] as $highlight)
                                            <span>
                                                <i class="fa-solid fa-check"></i>
                                                {{ $highlight }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- end slider items -->
        <!-- start vertical slider navigation -->
        <div class="hero-navigation">
            <button class="hero-arrow hero-prev"
                    type="button"
                    data-bs-target="#heroSlider"
                    data-bs-slide="prev"
                    aria-label="Previous slide">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <div class="hero-pagination">
                @foreach ($slides as $slide)
                    <button type="button"
                            data-bs-target="#heroSlider"
                            data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
            <button class="hero-arrow hero-next"
                    type="button"
                    data-bs-target="#heroSlider"
                    data-bs-slide="next"
                    aria-label="Next slide">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
        </div>
        <!-- end vertical slider navigation -->
    </div>
</section>
<!-- end hero slider section -->
@endif
<!-- start trust highlights section -->
@if (! empty($trustItems))
<section class="trust">
    <div class="container">
        <div class="row g-0">
            @foreach ($trustItems as $item)
                <div class="col-lg col-6">
                    <div class="trust-item {{ $loop->last ? 'trust-item-last' : '' }}">
                        <div class="trust-icon">
                            <i class="{{ $item['icon'] ?? '' }}"></i>
                        </div>
                        <div class="trust-content">
                            <span>{{ $item['label'] ?? '' }}</span>
                            <strong>{{ $item['title'] ?? '' }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!-- end trust highlights section -->
<!-- start about section -->
<section class="about-section" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- start about image -->
            <div class="col-lg-6">
                <div class="about-visual">
                    <div class="about-image-frame">
                        <img
                            class="about-img"
                            src="{{ $page->mediaUrl($about['image'] ?? null) ?? $page->imageUrl() }}"
                            alt="{{ $about['image_alt'] ?? $profile->name() }}"
                            loading="lazy"
                        />
                    </div>
                    <div class="about-image-accent"></div>
                    @if (! empty($about['experience']))
                        <div class="about-experience">
                            <div class="experience-number">
                                {{ $about['experience'] }}
                            </div>
                            <div class="experience-content">
                                <span>{{ $about['experience_label'] ?? trim((site_label('years_of') ?? '').' '.(site_label('experience') ?? '')) }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="about-small-label">
                        <i class="fa-solid fa-sparkles"></i>
                        {{ site_label('beautifully_planned') ?? '' }}
                    </div>
                </div>
            </div>
            <!-- end about image -->
            <!-- start about content -->
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="about-eyebrow">
                        <span></span>
                        {{ $about['eyebrow'] ?? '' }}
                    </div>
                    <h2>
                        {!! $about['heading'] ?? '' !!}
                    </h2>
                    <p class="about-lead">
                        {{ $about['lead'] ?? '' }}
                    </p>
                    <p>
                        {{ $about['text'] ?? '' }}
                    </p>
                    @if (! empty($about['stats']))
                        <!-- start about stats -->
                        <div class="about-stats">
                            @foreach ($about['stats'] as $stat)
                                <div class="about-stat">
                                    <strong>{{ $stat['value'] ?? '' }}</strong>
                                    <span>{{ $stat['label'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                        <!-- end about stats -->
                    @endif
                    <!-- start about action -->
                    <div class="about-action">
                        <a href="#contact" class="about-btn">
                            {{ $about['cta_label'] ?? '' }}
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <div class="about-location">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ $about['location'] ?? '' }}</span>
                        </div>
                    </div>
                    <!-- end about action -->
                </div>
            </div>
            <!-- end about content -->
        </div>
    </div>
</section>
<!-- end about section -->
<!-- start services section -->
<section class="services-section" id="services">
    <div class="container">
        <!-- start section heading -->
        <div class="services-heading">
            <div class="services-eyebrow">
                <span></span>
                {{ $servicesHeading['eyebrow'] ?? '' }}
                <span></span>
            </div>
            <h2>
                {!! $servicesHeading['heading'] ?? '' !!}
            </h2>
            <p>
                {{ $servicesHeading['text'] ?? '' }}
            </p>
        </div>
        <!-- end section heading -->
        <!-- start services grid -->
        <div class="row g-4">
            @forelse ($services->take(8) as $service)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <article class="service-card">
                        <a href="{{ $service->url() }}"
                           class="service-card-hit"
                           aria-label="Explore {{ $service->name }}"></a>
                        <div class="service-image-wrap">
                            <img
                                class="service-image"
                                src="{{ $service->imageUrl() }}"
                                alt="{{ $service->name }} in Uttar Pradesh"
                                loading="lazy"
                            />
                            <div class="service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="service-image-overlay"></div>
                        </div>
                        <div class="service-body">
                            <div class="service-icon">
                                <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                            </div>
                            <h3>{{ $service->short_name ?: $service->name }}</h3>
                            <p>{{ $service->shortDescription() }}</p>
                            <a href="{{ $service->url() }}" class="service-link">
                                Explore Service
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p>{{ site_label('content_home_services_empty') ?? '' }}</p>
                </div>
            @endforelse
        </div>
        <!-- end services grid -->
    </div>
</section>
<!-- end services section -->
<!-- start services cta section -->
<section class="services-cta">
    <div class="container">
        <div class="services-cta-inner">
            <div class="cta-decor cta-decor-one"></div>
            <div class="cta-decor cta-decor-two"></div>
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="cta-content">
                        <div class="cta-eyebrow">
                            <span></span>
                            {{ $servicesCta['eyebrow'] ?? '' }}
                        </div>
                        <h2>
                            {!! $servicesCta['heading'] ?? '' !!}
                        </h2>
                        <p>
                            {{ $servicesCta['text'] ?? '' }}
                        </p>
                        <a href="#contact" class="cta-btn">
                            {{ $servicesCta['button'] ?? '' }}
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="occasion-box">
                        <div class="occasion-heading">
                            <span class="occasion-line"></span>
                            <div>
                                <small>Perfect For</small>
                                <strong>Every Celebration</strong>
                            </div>
                        </div>
                        <div class="occasion-list">
                            @foreach (($servicesCta['occasions'] ?? []) as $occasion)
                                <span>
                                    <i class="{{ $occasion['icon'] ?? '' }}"></i>
                                    {{ $occasion['label'] ?? '' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end services cta section -->
<!-- start gallery section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="gallery-heading">
            <div class="gallery-eyebrow">
                <span></span>
                {{ $galleryHeading['eyebrow'] ?? '' }}
                <span></span>
            </div>
            <h2>
                {!! $galleryHeading['heading'] ?? '' !!}
            </h2>
            <p>
                {{ $galleryHeading['text'] ?? '' }}
            </p>
        </div>
        @if (! empty($galleryItems))
            <div class="gallery-slider"
                 data-gallery-slider
                 data-autoplay="5000"
                 aria-roledescription="carousel"
                 aria-label="{{ $galleryHeading['heading'] ?? 'Decoration gallery' }}">
                <div class="gallery-slider-viewport">
                    <div class="gallery-slider-track">
                        @foreach ($galleryItems as $item)
                            <div class="gallery-slide"
                                 role="group"
                                 aria-roledescription="slide"
                                 aria-label="{{ $loop->iteration }} of {{ $loop->count }}">
                                <div class="gallery-card">
                                    <img
                                        src="{{ $item['image'] }}"
                                        alt="{{ $item['title'] }} — {{ $profile->name() }}"
                                        loading="lazy">
                                    <div class="gallery-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="gallery-overlay">
                                        <div class="gallery-info">
                                            <span>{{ $item['category'] }}</span>
                                            <h3>{{ $item['title'] }}</h3>
                                        </div>
                                        <div class="gallery-arrow">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="gallery-slider-controls">
                    <button type="button"
                            class="gallery-slider-arrow gallery-slider-prev"
                            data-gallery-prev
                            aria-label="Previous gallery images">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    </button>
                    <div class="gallery-slider-dots" data-gallery-dots role="tablist" aria-label="Gallery pages"></div>
                    <button type="button"
                            class="gallery-slider-arrow gallery-slider-next"
                            data-gallery-next
                            aria-label="Next gallery images">
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>
<!-- end gallery section -->
<!-- Process Section Start -->
<section class="section process-section">
    <div class="container">
        <div class="title">
            <div class="eyebrow">{{ $process['eyebrow'] ?? '' }}</div>
            <h2>{{ $process['heading'] ?? '' }}</h2>
            <p>{{ $process['text'] ?? '' }}</p>
        </div>
        <div class="row g-4">
            @foreach (($process['steps'] ?? []) as $step)
                <div class="col-lg-3 col-md-6">
                    <div class="process">
                        <div class="process-icon">
                            <i class="{{ $step['icon'] ?? '' }}"></i>
                        </div>
                        <h3>{{ $step['title'] ?? '' }}</h3>
                        <p>{{ $step['text'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Process Section End -->
<!-- Packages Section Start -->
<section class="section packages-section" id="packages">
    <div class="container">
        <div class="title packages-title">
            <div class="eyebrow">{{ $packagesHeading['eyebrow'] ?? '' }}</div>
            <h2>{{ $packagesHeading['heading'] ?? '' }}</h2>
            <p>{{ $packagesHeading['text'] ?? '' }}</p>
        </div>
        <div class="row g-4">
            @foreach ($packages->take(3) as $package)
                <div class="col-lg-4">
                    <article class="package {{ $package->is_featured ? 'package-featured' : '' }}">
                        <div class="package-image">
                            <img
                                src="{{ $package->imageUrl() }}"
                                alt="{{ $package->image_alt ?: $package->name }}"
                                loading="lazy">
                            <span class="package-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            @if (filled($package->badge))
                                <span class="package-badge">{{ $package->badge }}</span>
                            @endif
                        </div>
                        <div class="package-body">
                            <h3>{{ $package->name }}</h3>
                            <div class="label">{{ $package->label }}</div>
                            <ul class="list-unstyled">
                                @foreach (collect($package->features)->filter()->take(6) as $feature)
                                    <li>
                                        <i class="fa-solid fa-check"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ $package->url() }}" class="btn-p">
                                View Details
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Packages Section End -->
@if (! empty($stats))
<!-- Stats Section Start -->
<section class="dark stats-section">
    <div class="container">
        <div class="row">
            @foreach ($stats as $stat)
                <div class="col-lg col-6">
                    <div class="bigstat">
                        <div class="bigstat-icon">
                            <i class="{{ $stat['icon'] ?? '' }}"></i>
                        </div>
                        <strong>{{ $stat['value'] ?? '' }}</strong>
                        <span>{{ $stat['label'] ?? '' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Stats Section End -->
@endif
@if (! empty($testimonialItems))
<!-- Testimonials Section Start -->
<section class="section testimonials-section" id="testimonials">
    <div class="container">
        <div class="title testimonials-title">
            <div class="eyebrow">{{ $testimonialsSection['eyebrow'] ?? '' }}</div>
            <h2>{{ $testimonialsSection['heading'] ?? '' }}</h2>
            <p>{{ $testimonialsSection['text'] ?? '' }}</p>
        </div>
        <div class="row g-4">
            @foreach ($testimonialItems as $testimonial)
                <div class="col-lg-4">
                    <article class="testimonial {{ $loop->index === 1 ? 'testimonial-featured' : '' }}">
                        <div class="testimonial-top">
                            <div class="quote">
                                <i class="fa-solid fa-quote-left"></i>
                            </div>
                        </div>
                        <p>“{{ $testimonial['quote'] ?? '' }}”</p>
                        <div class="client">
                            <div class="client-image">
                                <img src="{{ $page->mediaUrl($testimonial['image'] ?? null) ?? '' }}"
                                     alt="{{ $testimonial['name'] ?? '' }}"
                                     loading="lazy">
                            </div>
                            <div class="client-info">
                                <strong>{{ $testimonial['name'] ?? '' }}</strong>
                                <span>{{ $testimonial['event'] ?? '' }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Testimonials Section End -->
@endif
<!-- Blog Section Start -->
<section class="section blog-section" id="blog">
    <div class="container">
        <div class="title blog-title">
            <div class="eyebrow">{{ $blogsHeading['eyebrow'] ?? '' }}</div>
            <h2>{{ $blogsHeading['heading'] ?? '' }}</h2>
            <p>{{ $blogsHeading['text'] ?? '' }}</p>
        </div>
        <div class="row g-4">
            @foreach ($posts as $post)
                <div class="col-lg-4">
                    <article class="blog">
                        <div class="blog-image">
                            <img src="{{ $post->imageUrl() }}"
                                 alt="{{ $post->image_alt ?: $post->title }}"
                                 loading="lazy">
                            <span class="blog-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="blog-body">
                            <div class="meta">{{ $post->category?->name ?: 'Decoration Ideas' }}</div>
                            <h3><a href="{{ $post->url() }}">{{ $post->title }}</a></h3>
                            <p>{{ $post->excerpt }}</p>
                            <a class="link" href="{{ $post->url() }}">
                                Read More
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Blog Section End -->
<!-- FAQ Section Start -->
<section class="section faq-section" id="faq">
    <div class="container">
        <div class="title faq-title">
            <div class="eyebrow">Quick Answers</div>
            <h2>{{ $page->contentValue('copy.text_1') ?? '' }}</h2>
            <p>{{ site_label('content_home_faq_intro') ?? '' }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion faq-accordion" id="faqAcc">
                    @foreach (($faqs ?? []) as $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button
                                    class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#f{{ $loop->iteration }}">
                                    <span class="faq-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="faq-question">{{ $faq['question'] ?? '' }}</span>
                                </button>
                            </h3>
                            <div id="f{{ $loop->iteration }}"
                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                 data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FAQ Section End -->
<!-- Contact Section Start -->
<section class="section contact-section" id="contact">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="contact-content">
                    <div class="eyebrow">{{ $contact['eyebrow'] ?? "Let's Celebrate Together" }}</div>
                    <h2>
                        {!! $contact['heading'] ?? '' !!}
                    </h2>
                    <p class="contact-intro">
                        {{ $contact['text'] ?? '' }}
                    </p>
                    <div class="contact-details">
                        <div class="contact-detail">
                            <div class="contact-detail-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span>Call Us</span>
                                <strong>{{ $phone }}</strong>
                            </div>
                        </div>
                        <div class="contact-detail">
                            <div class="contact-detail-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span>Our Location</span>
                                <strong>{{ $profile->address() }}</strong>
                            </div>
                        </div>
                        <div class="contact-detail">
                            <div class="contact-detail-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span>Email Us</span>
                                <strong>{{ $profile->email() }}</strong>
                            </div>
                        </div>
                    </div>

                    @if (filled($profile->whatsappUrl()))
                        <a href="{{ $profile->whatsappUrl('Hello! I would like to discuss decoration for my event.') }}"
                           class="contact-whatsapp-btn"
                           target="_blank"
                           rel="noopener">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            Chat on WhatsApp
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-box">
                    <div class="contact-form-heading">
                        <span>Start Planning</span>
                        <h3>Tell Us About Your Event</h3>
                        <p>{{ site_label('content_home_contact_intro') ?? '' }}</p>
                    </div>
                    <x-enquiry-form
                        id-prefix="home"
                        source-page="home"
                        variant="home"
                    />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->
</main>
@endsection

