<!-- =========================================================
     HEADER START
     ========================================================= -->

@php
    $settings = \App\Models\SeoSetting::values();

    $currentPath = trim(request()->path(), '/');

    $isActive = function (string $url) use ($currentPath): bool {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if ($path === '') {
            return $currentPath === '';
        }

        return $currentPath === $path || str_starts_with($currentPath, $path.'/');
    };

    $hasCtaItem = collect($menuItems ?? [])->contains(fn (array $item): bool => (bool) ($item['is_cta'] ?? false));
    $headerCtaLabel = $settings['header_cta_label'] ?? '';
    $headerCtaUrl = $settings['header_cta_url'] ?? '';
@endphp

<header class="navwrap">

    <div class="container">

        <nav class="navbar navbar-expand-lg">

            <!-- Logo / Brand Start -->
            <a class="brand" href="{{ url('/') }}">

                <img
                    src="{{ $profile->logo() }}"
                    alt="{{ $profile->name() }} logo"
                >

            </a>
            <!-- Logo / Brand End -->


            <!-- Desktop Menu Start -->
            <div class="d-none d-lg-flex m-auto">

                <ul class="navbar-nav align-items-center">

                    @foreach ($menuItems ?? [] as $item)

                        @php
                            $children = $item['children'] ?? [];
                            $hasChildren = $children !== [];
                            $isMega = $hasChildren && ($item['is_mega'] ?? false);
                            $active = $isActive($item['url']);
                        @endphp

                        @if ($item['is_cta'] ?? false)

                            <li>
                                <a
                                    class="nav-link navcta ms-2 {{ $active ? 'active' : '' }}"
                                    href="{{ $item['url'] }}"
                                    @if (($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                                    data-cta="contact"
                                >
                                    {{ $item['label'] }}
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </li>

                        @elseif ($hasChildren && $isMega)

                            <!-- Mega Menu Item -->
                            <li class="nav-item dropdown mega-menu-item">

                                <a
                                    class="nav-link {{ $active ? 'active' : '' }}"
                                    href="{{ $item['url'] }}"
                                    aria-expanded="false"
                                >
                                    {{ $item['label'] }}
                                    <i class="fa-light fa-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu mega-menu">

                                    <div class="container">

                                        <div class="row g-0">

                                            @if (
                                                filled($settings['mega_kicker'] ?? null)
                                                || filled($settings['mega_title'] ?? null)
                                                || filled($settings['mega_text'] ?? null)
                                                || (filled($settings['mega_cta_label'] ?? null) && filled($settings['mega_cta_url'] ?? null))
                                            )
                                                <div class="col-lg-3 mega-intro">

                                                    @if (filled($settings['mega_kicker'] ?? null))
                                                        <span class="mega-kicker">{{ $settings['mega_kicker'] }}</span>
                                                    @endif

                                                    @if (filled($settings['mega_title'] ?? null))
                                                        <h3>{{ $settings['mega_title'] }}</h3>
                                                    @endif

                                                    @if (filled($settings['mega_text'] ?? null))
                                                        <p>{{ $settings['mega_text'] }}</p>
                                                    @endif

                                                    @if (filled($settings['mega_cta_label'] ?? null) && filled($settings['mega_cta_url'] ?? null))
                                                        <a href="{{ $settings['mega_cta_url'] }}" class="mega-cta" data-cta="contact">
                                                            {{ $settings['mega_cta_label'] }}
                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </a>
                                                    @endif

                                                </div>
                                            @endif

                                            @foreach ($children as $group)

                                                @php
                                                    $links = $group['children'] ?? [];
                                                @endphp

                                                @if ($links !== [])
                                                    <div class="col-lg-3 mega-column">

                                                        <div class="mega-heading">

                                                            @if (filled($group['group_icon'] ?? null))
                                                                <span class="mega-icon">
                                                                    <i class="{{ $group['group_icon'] }}"></i>
                                                                </span>
                                                            @endif

                                                            <div>
                                                                <strong>{{ $group['label'] }}</strong>

                                                                @if (filled($group['description'] ?? null))
                                                                    <small>{{ $group['description'] }}</small>
                                                                @endif
                                                            </div>

                                                        </div>

                                                        @foreach ($links as $link)
                                                            <a
                                                                href="{{ $link['url'] }}"
                                                                class="mega-link"
                                                                @if (($link['target'] ?? '_self') !== '_self') target="{{ $link['target'] }}" rel="noopener noreferrer" @endif
                                                            >
                                                                <span>
                                                                    @if (filled($link['icon'] ?? null))
                                                                        <i class="{{ $link['icon'] }}"></i>
                                                                    @endif
                                                                    {{ $link['label'] }}
                                                                </span>

                                                                <i class="fa-solid fa-arrow-right"></i>
                                                            </a>
                                                        @endforeach

                                                    </div>
                                                @else
                                                    <div class="col-lg-3 mega-column">

                                                        <a href="{{ $group['url'] }}" class="mega-link">
                                                            <span>
                                                                @if (filled($group['icon'] ?? null))
                                                                    <i class="{{ $group['icon'] }}"></i>
                                                                @endif
                                                                {{ $group['label'] }}
                                                            </span>

                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </a>

                                                    </div>
                                                @endif

                                            @endforeach

                                        </div>

                                        @if (
                                            filled($settings['mega_bottom_title'] ?? null)
                                            || filled($settings['mega_bottom_text'] ?? null)
                                            || (filled($settings['mega_bottom_link_label'] ?? null) && filled($settings['mega_bottom_link_url'] ?? null))
                                            || (filled($settings['mega_bottom_primary_label'] ?? null) && filled($settings['mega_bottom_primary_url'] ?? null))
                                        )
                                            <div class="mega-bottom">

                                                <div class="mega-bottom-text">

                                                    <i class="fa-solid fa-sparkles"></i>

                                                    <span>
                                                        @if (filled($settings['mega_bottom_title'] ?? null))
                                                            <strong>{{ $settings['mega_bottom_title'] }}</strong>
                                                        @endif

                                                        @if (filled($settings['mega_bottom_text'] ?? null))
                                                            {{ $settings['mega_bottom_text'] }}
                                                        @endif
                                                    </span>

                                                </div>

                                                <div class="mega-bottom-actions">

                                                    @if (filled($settings['mega_bottom_link_label'] ?? null) && filled($settings['mega_bottom_link_url'] ?? null))
                                                        <a href="{{ $settings['mega_bottom_link_url'] }}">
                                                            {{ $settings['mega_bottom_link_label'] }}
                                                            <i class="fa-solid fa-images"></i>
                                                        </a>
                                                    @endif

                                                    @if (filled($settings['mega_bottom_primary_label'] ?? null) && filled($settings['mega_bottom_primary_url'] ?? null))
                                                        <a href="{{ $settings['mega_bottom_primary_url'] }}" class="mega-bottom-primary" data-cta="contact">
                                                            {{ $settings['mega_bottom_primary_label'] }}
                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </a>
                                                    @endif

                                                </div>

                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </li>

                        @elseif ($hasChildren)

                            <!-- Standard Dropdown -->
                            <li class="nav-item dropdown">

                                <a
                                    class="nav-link {{ $active ? 'active' : '' }}"
                                    href="{{ $item['url'] }}"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{ $item['label'] }}
                                    <i class="fa-light fa-chevron-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    @foreach ($children as $child)
                                        <li>
                                            <a
                                                class="dropdown-item"
                                                href="{{ $child['url'] }}"
                                                @if (($child['target'] ?? '_self') !== '_self') target="{{ $child['target'] }}" rel="noopener noreferrer" @endif
                                            >
                                                {{ $child['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </li>

                        @else

                            <li>
                                <a
                                    class="nav-link {{ $active ? 'active' : '' }}"
                                    href="{{ $item['url'] }}"
                                    @if (($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                                >
                                    {{ $item['label'] }}
                                </a>
                            </li>

                        @endif

                    @endforeach

                    @if (! $hasCtaItem && filled($headerCtaLabel) && filled($headerCtaUrl))
                        <li>
                            <a class="nav-link navcta ms-2" href="{{ $headerCtaUrl }}" data-cta="contact">
                                {{ $headerCtaLabel }}
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </li>
                    @endif

                </ul>

            </div>
            <!-- Desktop Menu End -->


            <!-- Mobile Menu Button Start -->
            <button
                class="navbar-toggler d-lg-none mobile-menu-btn"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileOffcanvas"
                aria-controls="mobileOffcanvas"
                aria-label="Open navigation"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>
            <!-- Mobile Menu Button End -->

        </nav>

    </div>

</header>

<!-- =========================================================
     HEADER END
     ========================================================= -->
