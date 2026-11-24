{{-- About + highlights block. Renders only what the admin has written. --}}

@props([
    'seo',
    'tag' => null,
    'title' => null,
    'soft' => false,
])

@php
    $hasAbout = filled($seo['about'] ?? null);
    $highlights = $seo['highlights'] ?? [];
@endphp

@if ($hasAbout || ! empty($highlights))

    <section class="loc-section {{ $soft ? 'loc-section-soft' : '' }}" aria-labelledby="locAboutTitle">

        <div class="container">

            <div class="row g-5 align-items-start">

                <div class="col-lg-6">

                    @if (filled($tag))
                        <span class="loc-section-tag">{{ $tag }}</span>
                    @endif

                    @if (filled($title))
                        <h2 id="locAboutTitle" class="mb-3"
                            style="font-family: var(--font-heading); color: var(--theme-text-dark); font-size: clamp(1.5rem, 2.6vw, 2.1rem);">
                            {{ $title }}
                        </h2>
                    @endif

                    @if ($hasAbout)
                        <div class="loc-richtext">
                            {!! $seo['about'] !!}
                        </div>
                    @endif

                </div>

                @if (! empty($highlights))
                    <div class="col-lg-6">
                        <div class="loc-highlights">
                            @foreach ($highlights as $highlight)
                                <div class="loc-highlight">
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    <strong>{{ $highlight }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </section>

@endif
