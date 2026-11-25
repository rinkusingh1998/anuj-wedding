<!-- Footer Start -->
@php
    $settings = \App\Models\SeoSetting::values();

    $legalGroupName = site_label('footer_legal') ?? 'Legal';

    $footerGroups = collect($footerMenu ?? [])
        ->groupBy(fn (array $item): string => filled($item['group'] ?? null) ? $item['group'] : 'Links');

    $legalItems = $footerGroups->pull($legalGroupName, collect());

    $social = $profile->social();
    $whatsapp = $profile->whatsappUrl();

    $footerBackground = \App\Support\MediaLibrary::url($settings['footer_background_image'] ?? null);

    $hasContact = filled($profile->address())
        || filled($profile->phone())
        || filled($profile->email())
        || filled($settings['footer_working_hours'] ?? null);
@endphp

<footer class="site-footer">

    <!-- Footer Background Overlay Start -->
    <div class="footer-bg"
         @if (filled($footerBackground)) style="background-image:url('{{ $footerBackground }}');" @endif></div>
    <!-- Footer Background Overlay End -->


    <div class="container">

        <!-- Footer Main Row Start -->
        <div class="row g-5 footer-main">


            <!-- Brand Column Start -->
            <div class="col-lg-3 col-md-6">

                <div class="footer-brand">

                    <!-- Footer Logo Start -->
                    <div class="footer-brand-mark">

                        <a href="{{ url('/') }}" aria-label="{{ $profile->name() }}">

                            <img
                                src="{{ $profile->logo() }}"
                                alt="{{ $profile->name() }}"
                            >

                        </a>

                    </div>
                    <!-- Footer Logo End -->


                    @if (filled($profile->footerAbout()))
                        <!-- Footer Description Start -->
                        <p class="footer-description">
                            {{ $profile->footerAbout() }}
                        </p>
                        <!-- Footer Description End -->
                    @endif


                    @if ($social !== [] || filled($whatsapp))
                        <!-- Social Links Start -->
                        <div class="footer-social">

                            @if (filled($social['instagram'] ?? null))
                                <a
                                    href="{{ $social['instagram'] }}"
                                    aria-label="Instagram"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif

                            @if (filled($social['facebook'] ?? null))
                                <a
                                    href="{{ $social['facebook'] }}"
                                    aria-label="Facebook"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif

                            @if (filled($social['youtube'] ?? null))
                                <a
                                    href="{{ $social['youtube'] }}"
                                    aria-label="YouTube"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif

                            @if (filled($whatsapp))
                                <a
                                    href="{{ $whatsapp }}"
                                    aria-label="WhatsApp"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif

                        </div>
                        <!-- Social Links End -->
                    @endif

                </div>

            </div>
            <!-- Brand Column End -->


            @foreach ($footerGroups as $groupName => $items)

                @if ($items->isNotEmpty())
                    <!-- Footer Column: {{ $groupName }} -->
                    <div class="col-lg-2 col-md-4">

                        <div class="footer-column">

                            <h3 class="footer-title">
                                {{ $groupName }}
                            </h3>

                            <div class="footer-title-line"></div>

                            <ul class="footer-links list-unstyled">

                                @foreach ($items as $item)
                                    <li>
                                        <a
                                            href="{{ $item['url'] }}"
                                            @if (($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                                        >
                                            <i class="fa-solid fa-chevron-right"></i>

                                            <span>
                                                {{ $item['label'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                        </div>

                    </div>
                    <!-- Footer Column End -->
                @endif

            @endforeach


            @if ($hasContact)
                <!-- Contact Column Start -->
                <div class="col-lg-3 col-md-4">

                    <div class="footer-column">

                        <h3 class="footer-title">
                            {{ site_label('footer_contact') }}
                        </h3>

                        <div class="footer-title-line"></div>

                        <ul class="footer-contact list-unstyled">

                            @if (filled($profile->address()))
                                <li>

                                    <div class="footer-contact-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>

                                    <div>
                                        @if (filled($profile->mapUrl()))
                                            <a
                                                href="{{ $profile->mapUrl() }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                {{ $profile->address() }}
                                            </a>
                                        @else
                                            {{ $profile->address() }}
                                        @endif
                                    </div>

                                </li>
                            @endif

                            @if (filled($profile->phone()))
                                <li>

                                    <div class="footer-contact-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>

                                    <div>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}">
                                            {{ $profile->phone() }}
                                        </a>
                                    </div>

                                </li>
                            @endif

                            @if (filled($profile->email()))
                                <li>

                                    <div class="footer-contact-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>

                                    <div>
                                        <a href="mailto:{{ $profile->email() }}">
                                            {{ $profile->email() }}
                                        </a>
                                    </div>

                                </li>
                            @endif

                            @if (filled($settings['footer_working_hours'] ?? null))
                                <li>

                                    <div class="footer-contact-icon">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>

                                    <div>
                                        {{ $settings['footer_working_hours'] }}
                                    </div>

                                </li>
                            @endif

                        </ul>

                    </div>

                </div>
                <!-- Contact Column End -->
            @endif


        </div>
        <!-- Footer Main Row End -->


        <!-- Footer Bottom Start -->
        <div class="footer-bottom">

            <div class="row align-items-center g-3">

                <!-- Copyright Start -->
                <div class="col-md-6">

                    <p class="copyright">
                        © {{ date('Y') }}
                        {{ $profile->footerCopyright() }}
                    </p>

                </div>
                <!-- Copyright End -->


                @if ($legalItems->isNotEmpty())
                    <!-- Legal Links Start -->
                    <div class="col-md-6">

                        <div class="footer-legal">

                            @foreach ($legalItems as $item)

                                <a
                                    href="{{ $item['url'] }}"
                                    @if (($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" rel="noopener noreferrer" @endif
                                >
                                    {{ $item['label'] }}
                                </a>

                                @if (! $loop->last)
                                    <span></span>
                                @endif

                            @endforeach

                        </div>

                    </div>
                    <!-- Legal Links End -->
                @endif

            </div>

        </div>
        <!-- Footer Bottom End -->


    </div>

</footer>
<!-- Footer End -->
