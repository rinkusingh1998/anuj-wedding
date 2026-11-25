@extends('client.layout')

@section('content')
<main class="packages-page">
    <section class="contact-hero-section packages-hero-section"
        aria-labelledby="packagesHeroTitle">
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
                        Packages
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    {{ $page->contentValue('copy.text_1') ?? '' }}
                </span>
                <h1 id="packagesHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#packagesList"
                       class="contact-primary-btn">
                        Explore Packages
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
                        Customizable Packages
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Elegant Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Local Event Coverage
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="packages-intro-section">
        <div class="container">
            <div class="packages-section-heading">
                <span>
                    {{ $page->contentValue('copy.text_2') ?? '' }}
                </span>
                <h2>
                    {{ $page->contentValue('copy.text_3') ?? '' }}
                    <strong>Around Your Celebration</strong>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_4') ?? '' }}
                </p>
            </div>
        </div>
    </section>
        <section class="packages-list-section" id="packagesList">
        <div class="container">
            @if ($packages->isEmpty())
                <div class="packages-section-heading">
                    <span>PACKAGES</span>
                    <h2>{{ site_label('content_packages_empty') ?? '' }}</h2>
                </div>
            @else
                <div class="packages-grid">
                    @foreach ($packages as $package)
                        <article class="package-card {{ $package->is_featured ? 'package-card-featured' : '' }}">
                            @if (filled($package->badge))
                                <div class="package-popular">
                                    {{ $package->badge }}
                                </div>
                            @endif
                            <div class="package-card-image">
                                <img
                                    src="{{ $package->imageUrl() }}"
                                    alt="{{ $package->image_alt ?: $package->name }}"
                                    loading="lazy">
                                <span class="package-number">
                                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                @if (filled($package->label))
                                    <span class="package-category">
                                        {{ $package->label }}
                                    </span>
                                @endif
                            </div>
                            <div class="package-card-content">
                                @if (filled($package->eyebrow) || filled($package->label))
                                    <span class="package-label">
                                        {{ $package->eyebrow ?: $package->label }}
                                    </span>
                                @endif
                                <h3>{{ $package->name }}</h3>
                                @if (filled($package->description))
                                    <p>{{ $package->description }}</p>
                                @endif
                                @if (! empty($package->features))
                                    <ul>
                                        @foreach (collect($package->features)->filter()->take(6) as $feature)
                                            <li>
                                                <i class="fa-solid fa-check"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <a href="{{ $package->url() }}"
                                   class="package-btn">
                                    View Details
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="{{ url('/contact-us') }}"
                                   class="package-btn package-btn-outline"
                                   data-cta="package">
                                    Enquire Now
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
<section class="packages-custom-section">
        <div class="container">
            <div class="packages-custom-box">
                <div class="packages-custom-content">
                    <span>
                        {{ $page->contentValue('copy.text_5') ?? '' }}
                    </span>
                    <h2>
                        {{ $page->contentValue('copy.text_6') ?? '' }}
                        <strong>Package for Your Event</strong>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_7') ?? '' }}
                    </p>
                    <a href="{{ url('/contact-us') }}"
                       class="packages-custom-btn">
                        {{ $page->contentValue('copy.text_8') ?? '' }}
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="packages-custom-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
            </div>
        </div>
    </section>
    <section class="packages-benefits-section">
        <div class="container">
            <div class="packages-section-heading">
                <span>
                    WHY CHOOSE OUR PACKAGES
                </span>
                <h2>
                    Designed for a
                    <strong>Smoother Celebration</strong>
                </h2>
            </div>
            <div class="packages-benefits-grid">
                <div class="packages-benefit-item">
                    <div class="packages-benefit-icon">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <h3>
                        Thoughtful Styling
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_9') ?? '' }}
                    </p>
                </div>
                <div class="packages-benefit-item">
                    <div class="packages-benefit-icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h3>
                        Flexible Options
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_10') ?? '' }}
                    </p>
                </div>
                <div class="packages-benefit-item">
                    <div class="packages-benefit-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3>
                        Event Planning
                    </h3>
                    <p>
                        {{ $page->contentValue('copy.text_11') ?? '' }}
                    </p>
                </div>
                <div class="packages-benefit-item">
                    <div class="packages-benefit-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h3>
                        Nearby Coverage
                    </h3>
                    <p>
                        {{ site_setting('service_areas_intro') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="packages-final-section">
        <div class="container">
            <div class="packages-final-content">
                <span>
                    {{ $page->contentValue('copy.text_12') ?? '' }}
                </span>
                <h2>
                    Your Perfect Event
                    <strong>{{ $page->contentValue('copy.text_13') ?? '' }}</strong>
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_14') ?? '' }}
                </p>
                <div class="packages-final-actions">
                    <a href="{{ url('/contact-us') }}"
                       class="packages-final-btn">
                        Send An Enquiry
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                       class="packages-final-phone">
                        <i class="fa-solid fa-phone"></i>
                        {{ $profile->phone() }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

