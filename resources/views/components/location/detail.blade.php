{{-- =========================================================
     DETAIL BLOCK
     Image left + complete information right.
     Shared by service, state, city, locality and service-in-city
     pages so every detail page has the same layout.
========================================================= --}}

@props([
    'image',
    'title',
    'imageAlt' => null,
    'tag' => null,
    'tagline' => null,
    'description' => null,
    'about' => null,
    'highlights' => [],
    'meta' => [],
    'phone' => null,
    'whatsappUrl' => null,
    'bookService' => null,
    'bookLabel' => 'Send Enquiry',
])

<section class="loc-section loc-detail-section" aria-labelledby="locDetailTitle">

    <div class="container">

        <div class="loc-detail">

            <figure class="loc-detail-media">

                <img
                    src="{{ $image }}"
                    alt="{{ $imageAlt ?: $title }}"
                    loading="eager"
                >

                @if (filled($tag))
                    <figcaption class="loc-detail-media-badge">
                        <i class="fa-solid fa-medal" aria-hidden="true"></i>
                        {{ $tag }}
                    </figcaption>
                @endif

            </figure>

            <div class="loc-detail-body">

                @if (filled($tag))
                    <span class="loc-section-tag">{{ $tag }}</span>
                @endif

                <h2 id="locDetailTitle">{{ $title }}</h2>

                @if (filled($tagline))
                    <p class="loc-detail-tagline">{{ $tagline }}</p>
                @endif

                @if (filled($description))
                    <p class="loc-detail-description">{{ $description }}</p>
                @endif

                @if (filled($about))
                    <div class="loc-richtext loc-detail-about">
                        {!! $about !!}
                    </div>
                @endif

                @if (! empty($highlights))
                    <ul class="loc-detail-highlights">
                        @foreach ($highlights as $highlight)
                            @if (filled($highlight))
                                <li>
                                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                @if (! empty(array_filter($meta)))
                    <dl class="loc-detail-meta">
                        @foreach ($meta as $label => $value)
                            @if (filled($value))
                                <div>
                                    <dt>{{ $label }}</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                @endif

                <div class="loc-detail-actions">

                    @if (filled($phone))
                        <a href="tel:+{{ preg_replace('/\D/', '', $phone) }}"
                           class="loc-btn loc-btn-outline-dark"
                           data-cta="contact">
                            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
                            Call Now
                        </a>
                    @endif

                    @if (filled($whatsappUrl))
                        <a href="{{ $whatsappUrl }}"
                           class="loc-btn loc-btn-whatsapp"
                           target="_blank"
                           rel="noopener"
                           onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">
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
                        {{ $bookLabel }}
                    </button>

                </div>

            </div>

        </div>

    </div>

</section>
