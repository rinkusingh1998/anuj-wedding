{{-- =========================================================
     FIXED ENQUIRY BAR
     ========================================================= --}}

<div class="fixed-enquiry-bar">
    <div class="container">
        <div class="fixed-enquiry-inner">
            <div class="fixed-enquiry-content">
                <span class="fixed-enquiry-eyebrow">
                    NEED PROFESSIONAL EVENT DECORATION?
                </span>

                <h3>
                    Discuss your requirement with our team.
                </h3>
            </div>

            <button
                type="button"
                class="fixed-enquiry-btn"
                data-bs-toggle="modal"
                data-bs-target="#enquiryModal"
            >
                <span class="fixed-enquiry-btn-icon">
                    <i class="fa-solid fa-paper-plane"></i>
                </span>

                <span class="fixed-enquiry-btn-text">
                    Send Enquiry
                </span>

                <span class="fixed-enquiry-btn-arrow">
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </button>
        </div>
    </div>
</div>

{{-- =========================================================
     ENQUIRY MODAL
     ========================================================= --}}

<div
    class="modal fade enquiry-modal"
    id="enquiryModal"
    tabindex="-1"
    aria-labelledby="enquiryModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content enquiry-modal-content">

            {{-- Modal header --}}
            <div class="enquiry-modal-header">
                <div class="enquiry-modal-heading">
                    <span class="enquiry-modal-eyebrow">
                        LET'S PLAN YOUR CELEBRATION
                    </span>

                    <h2 id="enquiryModalLabel">
                        Tell Us About Your Event
                    </h2>

                    <p>
                        Share a few details and our event planning team
                        will get in touch with you.
                    </p>
                </div>

                <button
                    type="button"
                    class="enquiry-modal-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Modal body: the same enquiry form used on every page --}}
            <div class="enquiry-modal-body">
                <x-enquiry-form
                    id-prefix="enquiry"
                    source-page="popup"
                    variant="enquiry"
                    form-class="premium-enquiry-form"
                />
            </div>

            {{-- Modal footer: direct contact --}}
            <div class="enquiry-modal-footer">
                <span>
                    <i class="fa-solid fa-phone"></i>
                    Prefer to talk?
                </span>

                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone()) }}">
                    {{ $profile->phone() }}
                </a>

                <span class="enquiry-footer-divider"></span>

                <a
                    href="{{ $profile->whatsappUrl() }}"
                    target="_blank"
                    rel="noopener"
                >
                    <i class="fa-brands fa-whatsapp"></i>
                    WhatsApp Us
                </a>
            </div>
        </div>
    </div>
</div>
