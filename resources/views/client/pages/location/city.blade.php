@extends('client.layout')

{{--
    CITY LANDING PAGE  ( /{state}/{city} )

    Content, FAQs, images and SEO come from the admin panel
    (Locations > Cities). Hiding the city returns 404 everywhere.
--}}

@push('head_links')
    <link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}?v=20261125">
@endpush

@section('content')

<main class="loc-page">

    <x-location.hero :seo="$seo" :profile="$profile" :image="$city->imageUrl()" :title="$city->name" />

    <x-location.detail
        :image="$city->imageUrl()"
        :image-alt="$city->name.' decoration services'"
        :title="site_label('city_why_title', ['city' => $city->name], $city) ?: $city->name.' Decoration Services'"
        :tag="site_label('why_choose', [], $city)"
        :about="$seo['about'] ?? null"
        :highlights="$seo['highlights'] ?? []"
        :meta="[
            'City' => $city->name,
            'State' => $state->name,
            'Services' => $services->count().' decoration services',
        ]"
        :phone="$profile->phone()"
        :whatsapp-url="$profile->whatsappUrl('Hello! I need decoration services in '.$city->name.'. Please share the details and pricing.')"
        book-label="Send Enquiry"
    />

    <x-location.local-content
        :city="$city"
        :blocks="$city->contentValue('landing_blocks', [])"
    />

    @if (isset($localities) && $localities->isNotEmpty())
        <section class="loc-section loc-section-soft" aria-labelledby="locLocalitiesTitle">

            <div class="container">

                <div class="loc-section-head">

                    @if ($label = site_label('locality_areas', [], $city))
                        <span class="loc-section-tag">{{ $label }}</span>
                    @endif

                    @if ($title = site_label('locality_areas_title', ['city' => $city->name], $city))
                        <h2 id="locLocalitiesTitle">{{ $title }}</h2>
                    @endif

                </div>

                <div class="loc-areas justify-content-center d-flex">
                    @foreach ($localities as $locality)
                        <a href="{{ $locality->url() }}" class="loc-area-link">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            {{ $locality->name }}
                        </a>
                    @endforeach
                </div>

            </div>

        </section>
    @endif

    <x-location.areas
        :cities="$nearbyCities"
        :tag="site_label('nearby_areas', [], $city)"
        :title="site_label('nearby_title', ['city' => $city->name], $city)"
        :text="site_label('nearby_text', ['city' => $city->name], $city)"
        :soft="true"
    />

    <x-location.services
        :services="$services"
        :state="$state"
        :city="$city"
        :tag="site_label('our_services', [], $city)"
        :title="site_label('city_services_title', ['city' => $city->name], $city)"
        :text="site_label('services_text', ['city' => $city->name], $city)"
    />

    <x-location.faqs
        :faqs="$seo['faqs']"
        :tag="site_label('faq', [], $city)"
        :title="site_label('faq_title', ['city' => $city->name], $city)"
    />

    <x-location.areas
        :cities="$stateCities ?? collect()"
        :tag="$state->name"
        :title="site_label('other_cities_title', ['state' => $state->name], $city)"
        :text="site_label('other_cities_text', ['state' => $state->name], $city)"
    />

    <x-location.cta
        :profile="$profile"
        :title="site_label('cta_title', ['city' => $city->name], $city)"
        :text="site_label('cta_text', ['city' => $city->name], $city)"
        :contact-url="url('contact-us')"
    />

</main>

@endsection
