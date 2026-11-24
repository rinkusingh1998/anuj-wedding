@props([
    'city',
    'blocks' => [],
])

@php
    $blocks = is_array($blocks) ? $blocks : [];
    $coverage = collect($blocks['coverage'] ?? [])->filter()->values();
    $venues = collect($blocks['venues'] ?? [])->filter()->values();
    $styles = collect($blocks['styles'] ?? [])->filter()->values();
    $heading = $blocks['heading'] ?? 'Wedding Decoration Across '.$city->name;
    $intro = $blocks['intro'] ?? null;
@endphp

@if ($coverage->isNotEmpty() || $venues->isNotEmpty() || $styles->isNotEmpty())
    <section class="loc-section loc-local-section" aria-labelledby="localCoverageTitle">
        <div class="container">
            <div class="loc-section-head">
                <span class="loc-section-tag">Local Coverage</span>
                <h2 id="localCoverageTitle">{{ $heading }}</h2>
                @if (filled($intro))
                    <p>{{ $intro }}</p>
                @endif
            </div>

            @if ($coverage->isNotEmpty())
                <div class="loc-local-groups">
                    @foreach ($coverage as $group)
                        <article class="loc-local-card">
                            <span class="loc-local-icon">
                                <i class="{{ $group['icon'] ?? 'fa-solid fa-location-dot' }}" aria-hidden="true"></i>
                            </span>
                            <h3>{{ $group['title'] ?? 'Areas we cover' }}</h3>
                            @if (filled($group['text'] ?? null))
                                <p>{{ $group['text'] }}</p>
                            @endif
                            <div class="loc-local-chips">
                                @foreach (($group['areas'] ?? []) as $area)
                                    <span>{{ $area }}</span>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            <div class="loc-local-detail-grid">
                @if ($styles->isNotEmpty())
                    <article class="loc-local-panel">
                        <span class="loc-section-tag">Decoration Styles</span>
                        <h3>Setups We Create in {{ $city->name }}</h3>
                        <div class="loc-local-list">
                            @foreach ($styles as $style)
                                <span>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    {{ $style }}
                                </span>
                            @endforeach
                        </div>
                    </article>
                @endif

                @if ($venues->isNotEmpty())
                    <article class="loc-local-panel">
                        <span class="loc-section-tag">Venue Experience</span>
                        <h3>Venue Types We Decorate</h3>
                        <div class="loc-local-list">
                            @foreach ($venues as $venue)
                                <span>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    {{ $venue }}
                                </span>
                            @endforeach
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </section>
@endif
