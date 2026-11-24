@php
    $settings = \App\Models\SeoSetting::values();

    $mobileIntroKicker = $settings['mobile_intro_kicker'] ?? '';
    $mobileIntroTitle = $settings['mobile_intro_title'] ?? '';
    $mobileEnquiryLabel = $settings['mobile_enquiry_label'] ?? '';
    $mobileEnquiryTitle = $settings['mobile_enquiry_title'] ?? '';
    $mobileEnquiryText = $settings['mobile_enquiry_text'] ?? '';
    $mobileEnquiryButtonLabel = $settings['mobile_enquiry_button_label'] ?? '';
    $mobileEnquiryButtonUrl = $settings['mobile_enquiry_button_url'] ?? '';
@endphp

<!-- =========================================================
     MOBILE OFFCANVAS START
     ========================================================= -->

<div
    class="offcanvas offcanvas-end mobile-offcanvas"
    tabindex="-1"
    id="mobileOffcanvas"
    aria-labelledby="mobileOffcanvasLabel"
>


    <!-- =====================================================
         OFFCANVAS HEADER START
         ===================================================== -->

    <div class="offcanvas-header">

        <a
            href="{{ url('/') }}"
            class="mobile-offcanvas-brand"
            data-bs-dismiss="offcanvas"
        >

            <span class="mobile-brand-logo">

                <img
                    src="{{ $profile->mobileLogo() }}"
                    alt="{{ $profile->name() }} logo"
                >

            </span>

        </a>


        <!-- Close Button -->

        <button
            type="button"
            class="mobile-close-btn"
            data-bs-dismiss="offcanvas"
            aria-label="Close navigation"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>

    </div>

    <!-- =====================================================
         OFFCANVAS HEADER END
         ===================================================== -->


    <!-- =====================================================
         OFFCANVAS BODY START
         ===================================================== -->

    <div class="offcanvas-body">


        @if (filled($mobileIntroKicker) || filled($mobileIntroTitle))
            <!-- =================================================
                 INTRO TAG START
                 ================================================= -->

            <div class="mobile-menu-intro">

                <span class="mobile-menu-intro-icon">
                    <i class="fa-solid fa-sparkles"></i>
                </span>

                <div>
                    @if (filled($mobileIntroKicker))
                        <small>
                            {{ $mobileIntroKicker }}
                        </small>
                    @endif

                    @if (filled($mobileIntroTitle))
                        <strong>
                            {{ $mobileIntroTitle }}
                        </strong>
                    @endif
                </div>

            </div>

            <!-- =================================================
                 INTRO TAG END
                 ================================================= -->
        @endif


        <!-- =================================================
             MOBILE NAVIGATION START
             ================================================= -->

        <nav class="mobile-navigation">

            @foreach ($mobileMenu ?? [] as $rootIndex => $item)

                @php
                    $children = $item['children'] ?? [];
                    $hasChildren = $children !== [];
                @endphp

                @if ($hasChildren)

                    <!-- Mobile Accordion: {{ $item['label'] }} -->

                    <div class="mobile-services-accordion">

                        <button
                            class="mobile-nav-item mobile-services-toggle collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#mobileMenuAccordion{{ $rootIndex }}"
                            aria-expanded="false"
                            aria-controls="mobileMenuAccordion{{ $rootIndex }}"
                        >

                            <span class="mobile-nav-left">

                                <span class="mobile-nav-icon">
                                    <i class="{{ $item['icon'] ?? 'fa-solid fa-bars' }}"></i>
                                </span>

                                <span class="mobile-nav-text">
                                    {{ $item['label'] }}
                                </span>

                            </span>

                            <span class="mobile-services-chevron">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>

                        </button>


                        <div
                            class="collapse"
                            id="mobileMenuAccordion{{ $rootIndex }}"
                        >

                            <div class="mobile-services-panel">

                                <a
                                    href="{{ $item['url'] }}"
                                    class="mobile-all-services"
                                    data-bs-dismiss="offcanvas"
                                >

                                    <span>
                                        <i class="fa-solid fa-grid-2"></i>

                                        <strong>
                                            {{ site_label('view_all_services') }}
                                        </strong>
                                    </span>

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>


                                @foreach ($children as $childIndex => $child)

                                    @php $links = $child['children'] ?? []; @endphp

                                    @if ($links !== [])

                                        <!-- Mobile Category: {{ $child['label'] }} -->

                                        <div class="mobile-service-category">

                                            <button
                                                class="mobile-category-toggle collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#mobileMenu{{ $rootIndex }}Category{{ $childIndex }}"
                                                aria-expanded="false"
                                                aria-controls="mobileMenu{{ $rootIndex }}Category{{ $childIndex }}"
                                            >

                                                <span class="mobile-category-left">

                                                    @if (filled($child['group_icon'] ?? null))
                                                        <span class="mobile-category-icon">
                                                            <i class="{{ $child['group_icon'] }}"></i>
                                                        </span>
                                                    @endif

                                                    <span class="mobile-category-content">

                                                        <strong>
                                                            {{ $child['label'] }}
                                                        </strong>

                                                        @if (filled($child['description'] ?? null))
                                                            <small>
                                                                {{ $child['description'] }}
                                                            </small>
                                                        @endif

                                                    </span>

                                                </span>

                                                <span class="mobile-category-chevron">
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </span>

                                            </button>


                                            <div
                                                class="collapse"
                                                id="mobileMenu{{ $rootIndex }}Category{{ $childIndex }}"
                                            >

                                                <div class="mobile-service-links">

                                                    @foreach ($links as $link)
                                                        <a
                                                            href="{{ $link['url'] }}"
                                                            class="mobile-service-link"
                                                            data-bs-dismiss="offcanvas"
                                                        >
                                                            <span>
                                                                {{ $link['label'] }}
                                                            </span>

                                                            <i class="fa-solid fa-arrow-right"></i>
                                                        </a>
                                                    @endforeach

                                                </div>

                                            </div>

                                        </div>

                                        <!-- Mobile Category End -->

                                    @else

                                        <a
                                            href="{{ $child['url'] }}"
                                            class="mobile-service-link"
                                            data-bs-dismiss="offcanvas"
                                        >
                                            <span>
                                                {{ $child['label'] }}
                                            </span>

                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>

                                    @endif

                                @endforeach

                            </div>

                        </div>

                    </div>

                    <!-- Mobile Accordion End -->

                @else

                    <!-- Mobile Link: {{ $item['label'] }} -->

                    <a
                        href="{{ $item['url'] }}"
                        class="mobile-nav-item {{ $item['is_cta'] ?? false ? 'mobile-nav-cta' : '' }}"
                        data-bs-dismiss="offcanvas"
                        @if (($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                    >

                        <span class="mobile-nav-left">

                            <span class="mobile-nav-icon">
                                <i class="{{ $item['icon'] ?? 'fa-solid fa-circle' }}"></i>
                            </span>

                            <span class="mobile-nav-text">
                                {{ $item['label'] }}
                            </span>

                        </span>

                        <span class="mobile-nav-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>

                    </a>

                    <!-- Mobile Link End -->

                @endif

            @endforeach

        </nav>

        <!-- =================================================
             MOBILE NAVIGATION END
             ================================================= -->


        @if (filled($mobileEnquiryLabel) || filled($mobileEnquiryTitle) || filled($mobileEnquiryText) || (filled($mobileEnquiryButtonLabel) && filled($mobileEnquiryButtonUrl)))
            <!-- =================================================
                 MOBILE ENQUIRY CARD START
                 ================================================= -->

            <div class="mobile-enquiry-card">

                <div class="mobile-enquiry-decoration"></div>

                @if (filled($mobileEnquiryLabel))
                    <span class="mobile-enquiry-label">
                        {{ $mobileEnquiryLabel }}
                    </span>
                @endif

                @if (filled($mobileEnquiryTitle))
                    <h3>
                        {{ $mobileEnquiryTitle }}
                    </h3>
                @endif

                @if (filled($mobileEnquiryText))
                    <p>
                        {{ $mobileEnquiryText }}
                    </p>
                @endif


                @if (filled($mobileEnquiryButtonLabel) && filled($mobileEnquiryButtonUrl))
                    <a
                        href="{{ $mobileEnquiryButtonUrl }}"
                        class="mobile-enquiry-button"
                        data-bs-dismiss="offcanvas"
                        data-cta="contact"
                    >

                        <span>
                            {{ $mobileEnquiryButtonLabel }}
                        </span>

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>
                @endif

            </div>

            <!-- =================================================
                 MOBILE ENQUIRY CARD END
                 ================================================= -->
        @endif


    </div>

    <!-- =====================================================
         OFFCANVAS BODY END
         ===================================================== -->


    @if (filled($profile->phone()) || filled($profile->whatsapp()))
        <!-- =====================================================
             OFFCANVAS FOOTER START
             ===================================================== -->

        <div class="mobile-offcanvas-footer">

            <div class="mobile-contact-actions">

                @if (filled($profile->phone()))
                    <!-- Call -->

                    <a
                        href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
                        class="mobile-contact-action mobile-call-action"
                    >

                        <span class="mobile-contact-action-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>

                        <span>
                            <small>Talk to us</small>
                            <strong>{{ $profile->phone() }}</strong>
                        </span>

                    </a>
                @endif


                @if (filled($profile->whatsapp()))
                    <!-- WhatsApp -->

                    <a
                        href="{{ $profile->whatsappUrl() }}"
                        class="mobile-contact-action mobile-whatsapp-action"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <span class="mobile-contact-action-icon">
                            <i class="fab fa-whatsapp"></i>
                        </span>

                        <span>
                            <small>Quick enquiry</small>
                            <strong>WhatsApp Us</strong>
                        </span>

                    </a>
                @endif

            </div>

        </div>

        <!-- =====================================================
             OFFCANVAS FOOTER END
             ===================================================== -->
    @endif

</div>

<!-- =========================================================
     MOBILE OFFCANVAS END
     ========================================================= -->
