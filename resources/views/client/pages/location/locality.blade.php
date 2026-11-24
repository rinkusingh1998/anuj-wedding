@extends('client.layout')

{{--
    LOCALITY / AREA PAGE  ( /{state}/{city}/area/{locality} )

    Content, FAQs, images and SEO are managed in the admin panel
    (Locations > Localities).
--}}

@push('head_links')
    <link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}?v=20261121">
@endpush

@section('content')

<main class="loc-page">

    <x-location.hero
        :seo="$seo"
        :profile="$profile"
        :image="$locality->imageUrl()"
        :title="$locality->name"
    />

    <x-location.detail
        :image="$locality->imageUrl()"
        :image-alt="$locality->name.' decoration services'"
        :title="site_label('city_why_title', ['city' => $locality->name], $locality) ?: $locality->name.' Decoration Services'"
        :tag="site_label('why_choose', [], $locality)"
        :about="$seo['about'] ?? null"
        :highlights="$seo['highlights'] ?? []"
        :meta="[
            'Area' => $locality->name,
            'City' => $city->name,
            'State' => $state->name,
        ]"
        :phone="$profile->phone()"
        :whatsapp-url="$profile->whatsappUrl('Hello! I need decoration services in '.$locality->name.', '.$city->name.'. Please share the details.')"
        book-label="Send Enquiry"
    />

    <x-location.services
        :services="$services"
        :state="$state"
        :city="$city"
        :tag="site_label('our_services', [], $locality)"
        :title="site_label('city_services_title', ['city' => $locality->name], $locality)"
        :text="site_label('services_text', ['city' => $locality->name], $locality)"
    />

    @if ($nearbyLocalities->isNotEmpty())
        <section class="loc-section" aria-labelledby="locNearbyTitle">

            <div class="container">

                <div class="loc-section-head">

                    @if ($label = site_label('nearby_areas', [], $locality))
                        <span class="loc-section-tag">{{ $label }}</span>
                    @endif

                    @if ($title = site_label('nearby_title', ['city' => $city->name], $locality))
                        <h2 id="locNearbyTitle">{{ $title }}</h2>
                    @endif

                </div>

                <div class="loc-areas justify-content-center d-flex">
                    @foreach ($nearbyLocalities as $nearbyLocality)
                        <a href="{{ $nearbyLocality->url() }}" class="loc-area-link">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            {{ $nearbyLocality->name }}
                        </a>
                    @endforeach
                </div>

            </div>

        </section>
    @endif

    <x-location.faqs
        :faqs="$seo['faqs']"
        :tag="site_label('faq', [], $locality)"
        :title="site_label('faq_title', ['city' => $locality->name], $locality)"
    />

    <x-location.cta
        :profile="$profile"
        :title="site_label('cta_title', ['city' => $locality->name], $locality)"
        :text="site_label('cta_text', ['city' => $locality->name], $locality)"
        :contact-url="url('contact-us')"
    />

</main>

@endsection
