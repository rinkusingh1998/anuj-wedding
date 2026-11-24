@props(['blocks' => []])

{{--
    Flexible page blocks (admin panel > Page > Flexible Content Blocks).

    Each block has a type and a set of key/value fields. Only blocks the admin
    marked visible are rendered, in their configured order. A block with no
    content renders nothing at all.
--}}

@php
    $blocks = collect(is_iterable($blocks) ? $blocks : [])
        ->filter(fn ($block): bool => is_object($block) || is_array($block))
        ->filter(function ($block): bool {
            $visible = is_array($block) ? ($block['is_visible'] ?? true) : ($block->is_visible ?? true);

            return (bool) $visible;
        })
        ->values();
@endphp

@if ($blocks->isNotEmpty())
    <section class="cms-content-sections" aria-label="Page sections">
        @foreach ($blocks as $block)
            @php
                $isArray = is_array($block);
                $type = $isArray ? ($block['type'] ?? 'text') : ($block->type ?? 'text');
                $key = $isArray ? ($block['key'] ?? '') : ($block->key ?? '');
                $label = $isArray ? ($block['label'] ?? null) : ($block->label ?? null);
                $fields = $isArray ? ($block['fields'] ?? []) : ($block->fields ?? []);
                $fields = is_array($fields) ? $fields : [];

                $heading = $fields['heading'] ?? null;
                $body = $fields['body'] ?? ($fields['text'] ?? null);
                $image = \App\Support\MediaLibrary::url($fields['image'] ?? null);
                $buttonLabel = $fields['button_label'] ?? null;
                $buttonUrl = $fields['button_url'] ?? null;
                $hasContent = filled($heading) || filled($body) || filled($image);
            @endphp

            @continue(! $hasContent && $type !== 'html')

            @if ($type === 'html')
                @if (filled($fields['html'] ?? null))
                    <div class="cms-content-section">
                        <div class="container">
                            {!! $fields['html'] !!}
                        </div>
                    </div>
                @endif
            @elseif ($type === 'cta')
                <div class="cms-content-section cms-content-section-alt">
                    <div class="container">
                        <div class="cms-content-copy text-center">
                            @if (filled($heading))
                                <h2 class="cms-content-title">{{ $heading }}</h2>
                            @endif

                            @if (filled($body))
                                <div class="cms-content-body">{!! $body !!}</div>
                            @endif

                            @if (filled($buttonLabel) && filled($buttonUrl))
                                <a class="cms-content-button" href="{{ $buttonUrl }}" data-cta="contact">
                                    {{ $buttonLabel }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif ($type === 'list')
                <div class="cms-content-section">
                    <div class="container">
                        <div class="cms-content-copy">
                            @if (filled($heading))
                                <h2 class="cms-content-title">{{ $heading }}</h2>
                            @endif

                            @php
                                $items = collect(preg_split('/\r\n|\r|\n/', (string) ($fields['items'] ?? '')))
                                    ->map(fn ($item): string => trim((string) $item))
                                    ->filter()
                                    ->values();
                            @endphp

                            @if ($items->isNotEmpty())
                                <ul class="cms-content-list">
                                    @foreach ($items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="cms-content-section {{ $loop->even ? 'cms-content-section-alt' : '' }}">
                    <div class="container">
                        <div class="cms-content-grid {{ $image ? 'has-image' : 'no-image' }}">
                            <div class="cms-content-copy">
                                @if (filled($label))
                                    <span class="cms-content-eyebrow">{{ $label }}</span>
                                @endif

                                @if (filled($heading))
                                    <h2 class="cms-content-title">{{ $heading }}</h2>
                                @endif

                                @if (filled($body))
                                    <div class="cms-content-body">{!! $body !!}</div>
                                @endif

                                @if (filled($buttonLabel) && filled($buttonUrl))
                                    <a class="cms-content-button" href="{{ $buttonUrl }}">
                                        {{ $buttonLabel }}
                                    </a>
                                @endif
                            </div>

                            @if ($image)
                                <div class="cms-content-media">
                                    <img src="{{ $image }}"
                                         alt="{{ $fields['image_alt'] ?? $heading ?? $label ?? $key }}"
                                         loading="lazy"
                                         decoding="async">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </section>
@endif
