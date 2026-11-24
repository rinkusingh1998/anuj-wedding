@extends('client.layout')

@section('content')
<main class="sitemap-page">
    <section class="contact-hero-section sitemap-hero-section"
        aria-labelledby="sitemapHeroTitle">
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
                        Sitemap
                    </span>
                </nav>
                <span class="contact-eyebrow">
                    Website Sitemap
                </span>
                <h1 id="sitemapHeroTitle">
                    {!! $seo['heading'] ?? '' !!}
                </h1>
                <p>
                    {!! $seo['intro'] ?? '' !!}
                </p>
                <div class="contact-hero-actions">
                    <a href="#sitemapDirectory"
                       class="contact-primary-btn">
                        Explore Sitemap
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
                        Event & Function Decoration
                    </span>
                    <span>
                        <i class="fa-solid fa-check"></i>
                        Local Event Specialists
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="sitemap-intro-section"
        aria-labelledby="sitemapIntroTitle">
        <div class="container">
            <div class="sitemap-intro-layout">
                <div class="sitemap-intro-content">
                    <span class="sitemap-section-eyebrow">
                        Find What You Need
                    </span>
                    <h2 id="sitemapIntroTitle">
                        Everything You Need,
                        <span>In One Place</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_1') ?? '' }}
                    </p>
                    <p>
                        {{ $page->contentValue('copy.text_2') ?? '' }}
                    </p>
                </div>
                <div class="sitemap-intro-highlight">
                    <div class="sitemap-highlight-icon">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div class="sitemap-highlight-content">
                        <strong>
                            {{ $page->contentValue('copy.text_3') ?? '' }}
                        </strong>
                        <span>
                            {{ $page->contentValue('copy.text_4') ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="sitemap-directory-section"
        id="sitemapDirectory"
        aria-labelledby="sitemapDirectoryTitle">
        <div class="container">
            <div class="sitemap-section-heading">
                <span class="sitemap-section-eyebrow">
                    Website Directory
                </span>
                <h2 id="sitemapDirectoryTitle">
                    Browse Our Website
                </h2>
                <p>
                    {{ $page->contentValue('copy.text_5') ?? '' }}
                </p>
            </div>
            <div class="sitemap-group">
                <div class="sitemap-group-heading">
                    <div class="sitemap-group-icon">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <div>
                        <span>01</span>
                        <h3>Main Website Pages</h3>
                    </div>
                </div>
                <div class="sitemap-link-grid">
                    <a href="{{ url('/') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-house"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Home</strong>
                            <small>{{ site_label('content_sitemap_services_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/about-us') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-users"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>About Us</strong>
                            <small>{{ site_label('content_sitemap_about_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/services') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Services</strong>
                            <small>{{ site_label('content_sitemap_all_services_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/gallery') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-images"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Gallery</strong>
                            <small>{{ site_label('content_sitemap_gallery_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/packages') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Packages</strong>
                            <small>{{ site_label('content_sitemap_packages_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/blog') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-pen-nib"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Blog</strong>
                            <small>{{ site_label('content_sitemap_blogs_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/faq') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-circle-question"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>FAQ</strong>
                            <small>{{ site_label('content_sitemap_faq_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}" class="sitemap-link-card">
                        <span class="sitemap-link-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <span class="sitemap-link-content">
                            <strong>Contact Us</strong>
                            <small>{{ site_label('content_sitemap_contact_intro') ?? '' }}</small>
                        </span>
                        <i class="fa-solid fa-arrow-right sitemap-link-arrow"></i>
                    </a>
                </div>
            </div>
            <div class="sitemap-group">
                <div class="sitemap-group-heading">
                    <div class="sitemap-group-icon">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <span>02</span>
                        <h3>{{ $page->contentValue('copy.text_6') ?? '' }}</h3>
                    </div>
                </div>
                                <div class="sitemap-service-grid">
                    @foreach ($services->take(8) as $service)
                        <a href="{{ $service->url() }}"
                           class="sitemap-service-card">
                            <span class="sitemap-service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="sitemap-service-icon">
                                <i class="{{ $service->icon ?: 'fa-solid fa-wand-magic-sparkles' }}"></i>
                            </div>
                            <div>
                                <h4>{{ $service->name }}</h4>
                                <p>
                                    {{ $service->shortDescription() }}
                                </p>
                            </div>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endforeach
                </div>
            </div><div class="sitemap-group sitemap-resource-group">
                <div class="sitemap-group-heading">
                    <div class="sitemap-group-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <span>03</span>
                        <h3>{{ $page->contentValue('copy.text_16') ?? '' }}</h3>
                    </div>
                </div>
                <div class="sitemap-resource-grid">
                    <a href="{{ url('/blog') }}"
                       class="sitemap-resource-card">
                        <i class="fa-solid fa-lightbulb"></i>
                        <div>
                            <h4>Wedding Decoration Ideas</h4>
                            <p>
                                {{ $page->contentValue('copy.text_17') ?? '' }}
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('/faq') }}"
                       class="sitemap-resource-card">
                        <i class="fa-solid fa-circle-question"></i>
                        <div>
                            <h4>{{ $page->contentValue('copy.text_18') ?? '' }}</h4>
                            <p>
                                {{ $page->contentValue('copy.text_19') ?? '' }}
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('/gallery') }}"
                       class="sitemap-resource-card">
                        <i class="fa-solid fa-images"></i>
                        <div>
                            <h4>Decoration Gallery</h4>
                            <p>
                                {{ $page->contentValue('copy.text_20') ?? '' }}
                            </p>
                        </div>
                    </a>
                    <a href="{{ url('/packages') }}"
                       class="sitemap-resource-card">
                        <i class="fa-solid fa-box-open"></i>
                        <div>
                            <h4>Decoration Packages</h4>
                            <p>
                                {{ $page->contentValue('copy.text_21') ?? '' }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            @if (isset($visibleStates))
                @if (! empty($visibleStates))
                    <div class="sitemap-group">
                        <div class="sitemap-group-heading">
                            <div class="sitemap-group-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span>04</span>
                                <h3>Cities & Service Areas</h3>
                            </div>
                        </div>
                        <div class="sitemap-city-areas">
                            @foreach ($visibleStates as $state)
                                <div class="sitemap-city-group">
                                    <a href="{{ $state['url'] }}"
                                       class="sitemap-city-state">
                                        <i class="fa-solid fa-map"></i>
                                        {{ $state['name'] }}
                                    </a>
                                    <div class="sitemap-city-links">
                                        @foreach (array_slice($state['cities'], 0, 40) as $city)
                                            <a href="{{ $city['url'] }}">{{ $city['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="sitemap-city-cta">
                            <a href="{{ route('locations.index') }}"
                               class="sitemap-final-outline-btn">
                                View all service areas
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            <div class="sitemap-group sitemap-legal-group">
                <div class="sitemap-group-heading">
                    <div class="sitemap-group-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <span>05</span>
                        <h3>{{ $page->contentValue('copy.text_22') ?? '' }}</h3>
                    </div>
                </div>
                <div class="sitemap-legal-links">
                    <a href="{{ url('/privacy-policy') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>Privacy Policy</span>
                    </a>
                    <a href="{{ url('/terms-and-conditions') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>Terms & Conditions</span>
                    </a>
                    <a href="{{ url('/sitemap') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>Sitemap</span>
                    </a>
                    <a href="{{ url('/contact-us') }}">
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>Contact Information</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="sitemap-local-section"
        aria-labelledby="sitemapLocalTitle">
        <div class="container">
            <div class="sitemap-local-inner">
                <div class="sitemap-local-content">
                    @if (filled(site_label('service_areas')))
                        <span class="sitemap-section-eyebrow">{{ site_label('service_areas') }}</span>
                        <h2 id="sitemapLocalTitle">{{ site_label('service_areas') }}</h2>
                    @endif
                    @if (filled(site_setting('service_areas_intro')))
                        <p>{{ site_setting('service_areas_intro') }}</p>
                    @endif
                </div>
                <div class="sitemap-area-list">
                    @if (isset($visibleStates))
                        @foreach ($visibleStates as $state)
                            @foreach (array_slice($state['cities'], 0, 24) as $city)
                                <a href="{{ $city['url'] }}">{{ $city['name'] }}</a>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="sitemap-final-section"
        aria-labelledby="sitemapFinalTitle">
        <div class="container">
            <div class="sitemap-final-inner">
                <div>
                    <span class="sitemap-section-eyebrow">
                        {{ $page->contentValue('copy.text_23') ?? '' }}
                    </span>
                    <h2 id="sitemapFinalTitle">
                        {{ $page->contentValue('copy.text_24') ?? '' }}
                        <span>Worth Remembering</span>
                    </h2>
                    <p>
                        {{ $page->contentValue('copy.text_25') ?? '' }}
                    </p>
                </div>
                <div class="sitemap-final-actions">
                    <a href="{{ url('/services') }}"
                       class="sitemap-final-btn">
                        Explore Services
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/contact-us') }}"
                       class="sitemap-final-outline-btn">
                        Contact Us
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

