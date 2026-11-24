@extends('client.layout')

{{--
    GENERIC PAGE TEMPLATE (pages.template = "default")

    A page created in the admin panel renders with this template, so no new
    Blade file is needed for a new page. Every heading, paragraph, image, FAQ
    and button comes from the database:

      - hero text     : Page > Top / Hero Text
      - body          : Page > Body content (content.body) or about_content
      - sections      : Page > Extra Content Sections (rendered by the layout)
      - blocks        : Page > Flexible Content Blocks (rendered by the layout)
      - FAQs          : Page > FAQs
--}}

@section('content')

@php
    $heroImage = $page->imageUrl();
    $body = $page->contentValue('body');
    $body = filled($body) ? $body : $page->getAttribute('about_content');
    $faqs = collect($page->faqs ?? [])
        ->filter(fn ($faq): bool => is_array($faq) && filled($faq['question'] ?? null))
        ->values();
    $gallery = $page->galleryItems(12);
    $ctaLabel = site_setting('label_discuss_your_event');
@endphp

<main class="page-default">

    <section class="contact-hero-section" aria-labelledby="pageHeroTitle">

        <div class="contact-hero-overlay"></div>

        <div class="container">

            <div class="contact-hero-content">

                <nav class="contact-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">{{ $page->name }}</span>
                </nav>

                @if (filled($seo['eyebrow'] ?? null))
                    <span class="about-eyebrow">{{ $seo['eyebrow'] }}</span>
                @endif

                <h1 id="pageHeroTitle">
                    {!! $seo['heading'] ?: e($page->name) !!}
                </h1>

                @if (filled($seo['intro'] ?? null))
                    <p>{!! $seo['intro'] !!}</p>
                @endif

                @if (filled($ctaLabel))
                    <div class="contact-hero-actions">
                        <a href="{{ url('/contact-us') }}" class="contact-primary-btn" data-cta="contact">
                            {{ $ctaLabel }}
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif

            </div>

        </div>

    </section>

    @if (filled($body))
        <section class="all-services-section" aria-labelledby="pageBodyTitle">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="page-default-body">
                            {!! $body !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (! empty($gallery))
        <section class="gallery-section" aria-label="{{ $page->name }} gallery">
            <div class="container">
                <div class="all-services-grid">
                    @foreach ($gallery as $item)
                        <figure class="all-service-card">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['title'] }}"
                                 loading="lazy"
                                 decoding="async"
                                 style="width:100%;height:auto;border-radius:12px;">
                            @if (filled($item['title']))
                                <figcaption>{{ $item['title'] }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="faq-section" aria-labelledby="pageFaqTitle">
            <div class="container">
                <div class="all-services-heading">
                    <h2 id="pageFaqTitle">{{ site_label('faq_title') ?? 'FAQ' }}</h2>
                </div>

                <div class="accordion" id="pageFaqAccordion">
                    @foreach ($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="pageFaqHeading{{ $index }}">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#pageFaqBody{{ $index }}"
                                        aria-expanded="false"
                                        aria-controls="pageFaqBody{{ $index }}">
                                    {{ $faq['question'] }}
                                </button>
                            </h3>
                            <div id="pageFaqBody{{ $index }}"
                                 class="accordion-collapse collapse"
                                 aria-labelledby="pageFaqHeading{{ $index }}"
                                 data-bs-parent="#pageFaqAccordion">
                                <div class="accordion-body">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</main>

@endsection
