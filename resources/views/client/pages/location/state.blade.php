@extends('client.layout')

{{--
    STATE LANDING PAGE  ( /{state} )

    Every heading, paragraph, FAQ and image is managed in the admin panel.
    Section labels come from Global Settings > Section Labels.
--}}

@push('head_links')
    <link rel="stylesheet" href="{{ asset('assets/css/locations.css') }}?v=20261121">
@endpush

@section('content')

@php
    $districts = $cities->groupBy(fn ($city) => $city->district ?: $state->name);
@endphp

<main class="loc-page">

    <x-location.hero :seo="$seo" :profile="$profile" :image="$state->imageUrl()" :title="$state->name" />

    <x-location.detail
        :image="$state->imageUrl()"
        :image-alt="$state->name.' decoration services'"
        :title="site_label('state_why_title', ['state' => $state->name], $state) ?: $state->name.' Decoration Services'"
        :tag="site_label('why_choose', [], $state)"
        :about="$seo['about'] ?? null"
        :highlights="$seo['highlights'] ?? []"
        :meta="[
            'State' => $state->name,
            'Cities' => $cities->count().' cities covered',
            'Services' => $services->count().' decoration services',
        ]"
        :phone="$profile->phone()"
        :whatsapp-url="$profile->whatsappUrl('Hello! I need decoration services in '.$state->name.'. Please share the details and pricing.')"
        book-label="Send Enquiry"
    />

    @if ($cities->isNotEmpty())
        <section class="loc-section loc-section-soft" aria-labelledby="locDistrictTitle">

            <div class="container">

                <div class="loc-section-head">

                    @if ($label = site_label('state_districts', [], $state))
                        <span class="loc-section-tag">{{ $label }}</span>
                    @endif

                    @if ($title = site_label('state_districts_title', ['state' => $state->name], $state))
                        <h2 id="locDistrictTitle">{{ $title }}</h2>
                    @endif

                    @if ($text = site_label('state_districts_text', ['state' => $state->name], $state))
                        <p>{{ $text }}</p>
                    @endif

                </div>

                <div class="loc-districts">

                    @foreach ($districts as $district => $districtCities)
                        <div class="loc-district">

                            <h3>
                                <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                                {{ $district }}
                            </h3>

                            <div class="loc-areas">
                                @foreach ($districtCities as $districtCity)
                                    <a href="{{ $districtCity->url() }}" class="loc-area-link">
                                        {{ $districtCity->name }}
                                    </a>
                                @endforeach
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </section>
    @endif

    <x-location.services
        :services="$services"
        :state="$state"
        :tag="site_label('our_services', [], $state)"
        :title="site_label('services_title', ['state' => $state->name], $state)"
        :text="site_label('services_text', ['state' => $state->name], $state)"
    />

    <x-location.faqs
        :faqs="$seo['faqs']"
        :tag="site_label('faq', [], $state)"
        :title="site_label('faq_title', ['state' => $state->name], $state)"
    />

    <x-location.cta
        :profile="$profile"
        :title="site_label('cta_title', ['state' => $state->name], $state)"
        :text="site_label('cta_text', ['state' => $state->name], $state)"
        :contact-url="url('contact-us')"
    />

</main>

@endsection
