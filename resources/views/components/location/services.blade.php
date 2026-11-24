{{-- Services grid with image cards. Each card links to its own service page (or service-in-city page). --}}

@props([
    'services',
    'state' => null,
    'city' => null,
    'tag' => null,
    'title' => null,
    'text' => null,
    'profile' => null,
    'bookTitle' => null,
    'bookText' => null,
    'bookService' => null,
])

@if ($services->isNotEmpty())

    <section class="loc-section loc-section-soft" aria-labelledby="locServiceTitle">

        <div class="container">

            @if (filled($tag) || filled($title) || filled($text))
                <div class="loc-section-head">

                    @if (filled($tag))
                        <span class="loc-section-tag">{{ $tag }}</span>
                    @endif

                    @if (filled($title))
                        <h2 id="locServiceTitle">{{ $title }}</h2>
                    @endif

                    @if (filled($text))
                        <p>{{ $text }}</p>
                    @endif

                </div>
            @endif

            @if (filled($bookTitle))

                <div class="loc-book-banner">

                    <div class="loc-book-copy">
                        <span class="loc-book-eyebrow">Book Together</span>
                        <h3>{{ $bookTitle }}</h3>

                        @if (filled($bookText))
                            <p>{{ $bookText }}</p>
                        @endif
                    </div>

                    <div class="loc-book-actions">

                        @if ($profile?->phone())
                            <a href="tel:+{{ preg_replace('/\D/', '', $profile->phone()) }}"
                               class="loc-btn loc-btn-outline"
                               data-cta="contact">
                                <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
                                Call Now
                            </a>
                        @endif

                        @if ($profile?->whatsappUrl())
                            <a href="{{ $profile->whatsappUrl($bookService ? 'I would like to book '.$bookService.' along with other decoration services. Please share the details.' : null) }}"
                               class="loc-btn loc-btn-whatsapp"
                               target="_blank"
                               rel="noopener">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                WhatsApp
                            </a>
                        @endif

                        <button type="button"
                                class="loc-btn loc-btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#enquiryModal"
                                @if (filled($bookService)) data-service="{{ $bookService }}" @endif>
                            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                            Book These Services
                        </button>

                    </div>

                </div>

            @endif

            <div class="loc-grid loc-grid-services">

                @foreach ($services as $service)

                    @php
                        $url = $city ? $city->serviceUrl($service) : $service->url();
                        $label = $city ? $service->name.' in '.$city->name : $service->name;
                    @endphp

                    <a href="{{ $url }}" class="loc-card loc-card-service" data-cta="package">

                        <div class="loc-card-media">
                            <img
                                src="{{ $service->imageUrl() }}"
                                alt="{{ $label }}"
                                loading="lazy"
                            >
                        </div>

                        <div class="loc-card-body">

                            <h3>{{ $label }}</h3>

                            @if (filled($service->shortDescription()))
                                <p>{{ $service->shortDescription() }}</p>
                            @endif

                            <span class="loc-card-more">
                                View Details
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </span>

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

    </section>

@endif
