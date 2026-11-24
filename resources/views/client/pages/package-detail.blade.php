@extends('client.layout')

@section('content')

@php
    $features = collect($package->features ?? [])->filter()->values();
    $body = $package->contentValue('body');
    $gallery = $package->galleryItems(9);
@endphp

<main class="package-detail-page">

    <section class="contact-hero-section packages-hero-section" aria-labelledby="packageDetailHeroTitle">

        <div class="contact-hero-overlay"></div>

        <div class="container">

            <div class="contact-hero-content">

                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <a href="{{ url('/packages') }}">Packages</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">{{ $package->name }}</span>
                </nav>

                @php
                    $packageEyebrow = collect([
                        $package->badge,
                        $package->eyebrow ?: $package->label,
                    ])->filter()->implode(' · ');
                @endphp

                @if (filled($packageEyebrow))
                    <span class="contact-eyebrow">
                        {{ $packageEyebrow }}
                    </span>
                @endif

                <h1 id="packageDetailHeroTitle">{{ $package->name }}</h1>

                @if (filled($package->description))
                    <p>{{ $package->description }}</p>
                @endif

                <div class="contact-hero-actions">
                    <a href="{{ url('/contact-us') }}" class="contact-primary-btn" data-cta="package">
                        Enquire Now
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <a href="{{ url('/packages') }}" class="contact-outline-btn">
                        <i class="fa-solid fa-arrow-left"></i>
                        All Packages
                    </a>
                </div>

                @if (filled($package->price_from) || filled($package->price_note))
                    <div class="contact-hero-trust">
                        @if (filled($package->price_from))
                            <span>
                                <i class="fa-solid fa-tag"></i>
                                {{ $package->price_from }}
                            </span>
                        @endif

                        @if (filled($package->price_note))
                            <span>
                                <i class="fa-solid fa-circle-info"></i>
                                {{ $package->price_note }}
                            </span>
                        @endif
                    </div>
                @endif

            </div>

        </div>

    </section>

    <section class="about-content-section">
        <div class="container">
            <div class="row g-4">

                <div class="col-lg-8">
                    @if (filled($body))
                        <div class="package-detail-body">
                            {!! $body !!}
                        </div>
                    @endif

                    @if ($features->isNotEmpty())
                        <div class="package-card">
                            <div class="package-card-content">
                                <span class="package-label">Package Includes</span>

                                <ul>
                                    @foreach ($features as $feature)
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="package-card">
                        <div class="package-card-content">
                            <span class="package-label">Plan Your Celebration</span>

                            <h2>Need a custom setup?</h2>

                            <p>
                                Share your event date, city and requirements. Our team will
                                suggest a decoration plan that fits your venue and budget.
                            </p>

                            <a href="{{ url('/contact-us') }}" class="package-btn" data-cta="package">
                                Get a Quote
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if ($gallery !== [])
        <section class="gallery-section">
            <div class="container">
                <div class="row g-4">
                    @foreach ($gallery as $image)
                        <div class="col-lg-4 col-md-6">
                            <img src="{{ $image['image'] }}"
                                 alt="{{ $image['title'] ?: $package->name }}"
                                 loading="lazy"
                                 decoding="async"
                                 class="img-fluid w-100">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($otherPackages->isNotEmpty())
        <section class="packages-list-section">
            <div class="container">
                <div class="packages-section-heading">
                    <span>MORE OPTIONS</span>
                    <h2>Other Decoration Packages</h2>
                </div>

                <div class="packages-grid">
                    @foreach ($otherPackages as $otherPackage)
                        <article class="package-card">
                            <div class="package-card-image">
                                <img src="{{ $otherPackage->imageUrl() }}"
                                     alt="{{ $otherPackage->image_alt ?: $otherPackage->name }}"
                                     loading="lazy">

                                <span class="package-number">
                                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                @if (filled($otherPackage->label))
                                    <span class="package-category">
                                        {{ $otherPackage->label }}
                                    </span>
                                @endif
                            </div>

                            <div class="package-card-content">
                                @if (filled($otherPackage->label))
                                    <span class="package-label">
                                        {{ $otherPackage->label }}
                                    </span>
                                @endif

                                <h3>{{ $otherPackage->name }}</h3>

                                @if (filled($otherPackage->description))
                                    <p>{{ $otherPackage->description }}</p>
                                @endif

                                <a href="{{ $otherPackage->url() }}" class="package-btn">
                                    View Package
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</main>

@endsection
