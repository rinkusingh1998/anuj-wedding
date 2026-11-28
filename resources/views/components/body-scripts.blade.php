<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/premium-redesign.js') }}?v=20261127a"></script>
<script src="{{ asset('assets/js/tracking.js') }}?v=20261101"></script>

{{--
    Google Analytics 4 + conversion events.

    The measurement ID comes from the admin settings. Nothing is loaded when it
    is empty, so there are never duplicate or invalid tracking scripts.
--}}
@if (filled($analyticsId ?? null))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }

        @if (! ($analyticsRequireConsent ?? false))
            gtag('js', new Date());
            gtag('config', '{{ $analyticsId }}');
        @endif

        window.anujAnalytics = {
            ready: {{ ($analyticsRequireConsent ?? false) ? 'false' : 'true' }},
            measurementId: '{{ $analyticsId }}',

            enable: function () {
                if (this.ready) { return; }
                this.ready = true;
                gtag('js', new Date());
                gtag('config', this.measurementId);
            },

            event: function (name, params) {
                if (!this.ready) { return; }
                gtag('event', name, params || {});
            }
        };
    </script>
@endif

@if (session('enquiry_success'))
    <script>
        window.addEventListener('load', function () {
            if (window.anujAnalytics && typeof window.anujAnalytics.event === 'function') {
                window.anujAnalytics.event('generate_lead', {
                    form: 'enquiry',
                    page_location: window.location.href
                });
            }
        });
    </script>
@endif

{{-- Google Tag Manager (optional, admin controlled) --}}
@if (filled($tagManagerId ?? null))
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $tagManagerId }}');
    </script>
@endif
