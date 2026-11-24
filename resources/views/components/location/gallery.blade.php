@props(['images' => [], 'title' => null, 'tag' => null, 'alt' => 'Our recent decoration work'])

@php
    $images = collect(is_array($images) ? $images : [])
        ->map(function (mixed $image): ?string {
            if (is_array($image)) {
                return $image['image'] ?? null;
            }

            return is_string($image) ? $image : null;
        })
        ->filter()
        ->unique()
        ->take(12)
        ->values();

    $isMarquee = $images->count() > 1;
@endphp

@if ($images->isNotEmpty())
    <section class="loc-section loc-gallery-section" aria-labelledby="serviceGalleryTitle">
        <div class="container">
            <div class="loc-section-head loc-section-head-row">
                <div>
                    @if (filled($tag))
                        <span class="loc-section-tag">{{ $tag }}</span>
                    @endif

                    @if (filled($title))
                        <h2 id="serviceGalleryTitle">{{ $title }}</h2>
                    @endif
                </div>

                <p class="loc-gallery-hint">
                    <i class="fa-solid fa-arrows-left-right" aria-hidden="true"></i>
                    {{ $isMarquee ? 'Slow gallery — hover to pause the movement' : 'A closer look at our work' }}
                </p>
            </div>

            @if ($isMarquee)
                <div class="loc-gallery-marquee"
                     data-marquee-gallery
                     data-speed="0.36"
                     data-hover-speed="0.12"
                     aria-label="{{ $title ?: 'Decoration gallery' }}">
                    <div class="loc-gallery-track">
                        @for ($copy = 0; $copy < 2; $copy++)
                            @foreach ($images as $image)
                                <figure class="loc-gallery-item" @if ($copy === 1) aria-hidden="true" @endif>
                                    <img src="{{ $image }}"
                                         alt="{{ $copy === 0 ? $alt : '' }}"
                                         loading="lazy"
                                         decoding="async">
                                </figure>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @else
                <div class="loc-gallery-static">
                    @foreach ($images as $image)
                        <figure class="loc-gallery-item">
                            <img src="{{ $image }}"
                                 alt="{{ $alt }}"
                                 loading="lazy"
                                 decoding="async">
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
