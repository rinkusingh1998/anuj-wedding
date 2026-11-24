@extends('client.layout')

{{--
    SERVICE IN CITY PAGE  ( /{state}/{city}/{service} )

    The H1 is built from real data (service name + city name). All descriptive
    copy, FAQs and images are managed in the admin panel.
--}}

@push('head_links')
    <link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}?v=20261121">
@endpush

@section('content')

@php
    $serviceName = $service->short_name ?: $service->name;
    $heading = $serviceName.' in '.$city->name;

    $faqTitle = site_label('faq_title', ['city' => $city->name], $service);
    $faqTitle = filled($faqTitle) ? $heading.' — '.$faqTitle : null;
@endphp

<main class="loc-page">

    <x-location.hero
        :seo="$seo"
        :profile="$profile"
        :image="$city->imageUrl()"
        :title="$heading"
    />

    <x-location.detail
        :image="$service->imageUrl()"
        :image-alt="$serviceName.' in '.$city->name"
        :title="$heading"
        :tag="site_label('service_detail', [], $service)"
        :tagline="$service->tagline"
        :description="$service->description"
        :about="$seo['about'] ?? null"
        :highlights="$seo['highlights'] ?? []"
        :meta="[
            'Service' => $serviceName,
            'City' => $city->name,
            'State' => $state->name,
        ]"
        :phone="$profile->phone()"
        :whatsapp-url="$profile->whatsappUrl('Hello! I am interested in '.$serviceName.' in '.$city->name.'. Please share the details and pricing.')"
        :book-service="$service->name"
        book-label="Book This Service"
    />

    <x-location.services
        :services="$services"
        :state="$state"
        :city="$city"
        :tag="site_label('more_services', [], $service)"
        :title="site_label('more_services_title', ['city' => $city->name], $service)"
        :text="site_label('services_text', ['city' => $city->name], $service)"
    />

    <x-location.faqs
        :faqs="$seo['faqs']"
        :tag="site_label('faq', [], $service)"
        :title="$faqTitle"
    />

    <x-location.areas
        :cities="$nearbyCities"
        :tag="site_label('nearby_areas', [], $city)"
        :title="site_label('nearby_title', ['city' => $city->name], $city)"
        :soft="true"
    />

    <x-location.areas
        :cities="$stateCities ?? collect()"
        :tag="$state->name"
        :title="site_label('other_cities_title', ['state' => $state->name], $city)"
        :text="site_label('other_cities_text', ['state' => $state->name], $city)"
    />

    <x-location.cta
        :profile="$profile"
        :title="site_label('cta_title', ['service' => $serviceName, 'city' => $city->name], $service)"
        :text="site_label('cta_text', ['service' => $serviceName, 'city' => $city->name], $service)"
        :contact-url="url('contact-us')"
    />

</main>

@endsection
