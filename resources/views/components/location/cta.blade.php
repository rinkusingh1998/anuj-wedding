{{-- CTA band (call + WhatsApp). Hidden entirely when no contact details exist. --}}

@props([
    'profile',
    'title' => null,
    'text' => null,
    'contactUrl' => null,
    'whatsappMessage' => null,
])

@php
    $phone = $profile->phone();
    $whatsapp = $profile->whatsappUrl($whatsappMessage);
@endphp

@if (filled($title) || filled($phone) || filled($whatsapp))

    <section class="loc-cta" aria-labelledby="locCtaTitle">

        <div class="container">

            <div class="loc-cta-inner">

                <div>

                    @if (filled($title))
                        <h2 id="locCtaTitle">{{ $title }}</h2>
                    @endif

                    @if (filled($text))
                        <p>{{ $text }}</p>
                    @endif

                </div>

                <div class="loc-cta-actions">

                    @if (filled($phone))
                        <a href="tel:+{{ preg_replace('/\D/', '', $phone) }}"
                           class="loc-btn loc-btn-primary"
                           data-cta="contact">

                            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>

                            {{ $phone }}

                        </a>
                    @endif

                    @if (filled($whatsapp))
                        <a href="{{ $whatsapp }}"
                           class="loc-btn loc-btn-outline"
                           target="_blank"
                           rel="noopener"
                           onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });">

                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>

                            WhatsApp

                        </a>
                    @endif

                    @if ($contactUrl)
                        <a href="{{ $contactUrl }}" class="loc-btn loc-btn-outline" data-cta="contact">
                            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                            Contact Form
                        </a>
                    @endif

                </div>

            </div>

        </div>

    </section>

@endif
