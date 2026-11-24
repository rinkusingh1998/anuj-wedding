@props(['sections' => [], 'label' => 'Page content'])

@php
    $sections = collect(is_array($sections) ? $sections : [])
        ->filter(fn ($section): bool => is_array($section) && (
            filled($section['heading'] ?? null)
            || filled($section['body'] ?? null)
            || filled($section['image'] ?? null)
        ))
        ->values();
@endphp

@if ($sections->isNotEmpty())
    <section class="cms-content-sections" aria-label="{{ $label }}">

        @foreach ($sections as $section)
            @php
                $image = \App\Support\MediaLibrary::url($section['image'] ?? null);
                $position = $section['image_position'] ?? ($image ? 'right' : 'none');
                $hasImage = $image && $position !== 'none';
            @endphp

            <div class="cms-content-section {{ $loop->even ? 'cms-content-section-alt' : '' }}">

                <div class="container">

                    <div class="cms-content-grid {{ $hasImage ? 'has-image' : 'no-image' }} {{ $position === 'left' ? 'image-left' : '' }}">

                        <div class="cms-content-copy">

                            @if (filled($section['eyebrow'] ?? null))
                                <span class="cms-content-eyebrow">{{ $section['eyebrow'] }}</span>
                            @endif

                            @if (filled($section['heading'] ?? null))
                                <h2 class="cms-content-title">{{ $section['heading'] }}</h2>
                            @endif

                            @if (filled($section['body'] ?? null))
                                <div class="cms-content-body">
                                    {!! $section['body'] !!}
                                </div>
                            @endif

                            @if (filled($section['button_label'] ?? null) && filled($section['button_url'] ?? null))
                                <a class="cms-content-button" href="{{ $section['button_url'] }}">
                                    {{ $section['button_label'] }}
                                </a>
                            @endif

                        </div>

                        @if ($hasImage)
                            <div class="cms-content-media">
                                <img src="{{ $image }}"
                                     alt="{{ $section['heading'] ?? $label }}"
                                     loading="lazy">
                            </div>
                        @endif

                    </div>

                </div>

            </div>
        @endforeach

    </section>
@endif
