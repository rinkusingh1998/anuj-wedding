@php
    $areaStates = collect($visibleStates ?? []);
    $areaHeading = site_label('service_areas');
    $areaIntro = site_setting('service_areas_intro');
@endphp

@if ($areaStates->isNotEmpty())
    <section class="{{ $sectionClass ?? 'all-services-location-section' }}">
        <div class="container">
            <div class="{{ $boxClass ?? 'all-services-location-box' }}">
                <div class="{{ $contentClass ?? 'all-services-location-content' }}">
                    @if (filled($areaHeading))
                        <h2>{{ $areaHeading }}</h2>
                    @endif

                    @if (filled($areaIntro))
                        <p>{{ $areaIntro }}</p>
                    @endif
                </div>

                <div class="{{ $listClass ?? 'all-services-location-list' }}">
                    @foreach ($areaStates as $areaState)
                        @foreach ($areaState['cities'] as $areaCity)
                            <a href="{{ $areaCity['url'] }}">{{ $areaCity['name'] }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
