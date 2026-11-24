/**
 * Lead conversion tracking.
 *
 * Fires GA4 events only for real user interactions:
 *   phone_call_click     - any tel: link
 *   whatsapp_click       - any wa.me / api.whatsapp.com link
 *   contact_cta_click    - links/buttons marked data-cta="contact"
 *   package_enquiry_click- links/buttons marked data-cta="package"
 *   generate_lead        - fired once, only after a successful enquiry submit
 *
 * The analytics object is defined by components/body-scripts.blade.php and is
 * a no-op when no GA4 measurement ID is configured.
 */
(function () {
    'use strict';

    function track(name, params) {
        if (window.anujAnalytics && typeof window.anujAnalytics.event === 'function') {
            window.anujAnalytics.event(name, params || {});
        }
    }

    function linkParams(link) {
        return {
            link_url: link.href,
            link_text: (link.textContent || '').trim().slice(0, 80),
            page_location: window.location.href
        };
    }

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a, button');

        if (!link) {
            return;
        }

        var href = link.getAttribute('href') || '';
        var cta = link.getAttribute('data-cta') || '';

        if (href.indexOf('tel:') === 0) {
            track('phone_call_click', linkParams(link));
            return;
        }

        if (href.indexOf('wa.me') !== -1 || href.indexOf('api.whatsapp.com') !== -1) {
            track('whatsapp_click', linkParams(link));
            return;
        }

        if (cta === 'package') {
            track('package_enquiry_click', linkParams(link));
            return;
        }

        if (cta === 'contact') {
            track('contact_cta_click', linkParams(link));
        }
    }, true);

    // Enquiry form success (set by the contact form after a valid submission).
    window.addEventListener('anuj:lead', function (event) {
        track('generate_lead', event.detail || {});
    });
})();
