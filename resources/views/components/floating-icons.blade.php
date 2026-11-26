<!-- =========================================================
     FLOATING ACTION BUTTONS START
     ========================================================= -->

<div class="floating-actions">

    <!-- =====================================================
         CALL BUTTON START
         ===================================================== -->

    <a
        href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}"
        class="floating-action floating-call"
        aria-label="Call us"
    >

        <span class="floating-icon">
            <i class="fa-solid fa-phone"></i>
        </span>

        <span class="floating-text">
            <small>Talk to us</small>
            <strong>Call Now</strong>
        </span>

    </a>

    <!-- Call Button End -->


    <!-- =====================================================
         DESKTOP WHATSAPP BUTTON START
         ===================================================== -->

    <a
        href="{{ $profile->whatsappUrl() }}"
        onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });"
        class="floating-action floating-whatsapp floating-whatsapp-desktop"
        aria-label="WhatsApp us"
        target="_blank"
        rel="noopener noreferrer"
    >

        <span class="floating-icon">
            <i class="fab fa-whatsapp"></i>
        </span>

        <span class="floating-text">
            <small>Quick enquiry</small>
            <strong>WhatsApp</strong>
        </span>

    </a>

    <!-- Desktop WhatsApp Button End -->


    <!-- =====================================================
         MOBILE WHATSAPP BUTTON START
         ===================================================== -->

    <a
        href="{{ $profile->whatsappUrl() }}"
        onclick="gtag('event', 'whatsapp_click', { event_category: 'engagement', event_label: 'WhatsApp Button' });"
        class="floating-action floating-whatsapp floating-whatsapp-mobile"
        aria-label="WhatsApp us"
    >

        <span class="floating-icon">
            <i class="fab fa-whatsapp"></i>
        </span>

        <span class="floating-text">
            <small>Quick enquiry</small>
            <strong>WhatsApp</strong>
        </span>

    </a>

    <!-- Mobile WhatsApp Button End -->

</div>

<!-- =========================================================
     FLOATING ACTION BUTTONS END
     ========================================================= -->