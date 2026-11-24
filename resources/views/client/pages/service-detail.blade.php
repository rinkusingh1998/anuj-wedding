@extends('client.layout')

@section('content')

@php
    $service = $service ?? null;
@endphp

@if ($service)
    <main class="service-detail-page">

        <section class="contact-hero-section" aria-labelledby="serviceDetailHeroTitle">

            <div class="contact-hero-overlay"></div>

            <div class="container">

                <div class="contact-hero-content">

                    <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Home</a>
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        <a href="{{ url('/services') }}">Services</a>
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        <span aria-current="page">{{ $service->name }}</span>
                    </nav>

                    <h1 id="serviceDetailHeroTitle">{{ $service->name }}</h1>

                    @if (filled($service->intro_content))
                        <p>{!! $service->intro_content !!}</p>
                    @endif

                </div>

            </div>

        </section>

        @if (filled($service->about_content))
            <section class="about-content-section">
                <div class="container">
                    {!! $service->about_content !!}
                </div>
            </section>
        @endif

        @if ($service->galleryItems())
            <section class="gallery-section">
                <div class="container">
                    <div class="row g-4">
                        @foreach ($service->galleryItems(9) as $image)
                            <div class="col-lg-4 col-md-6">
                                <img src="{{ $image['image'] }}"
                                     alt="{{ $image['title'] ?: $service->name }}"
                                     loading="lazy"
                                     decoding="async"
                                     class="img-fluid w-100">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

    </main>
@endif

@endsection
