@props([
    'id',
    'icon',
    'title',
    'subtitle',
    'services' => [],
    'requiredOption' => null,
])

{{--
    One accordion group of the enquiry form's service picker.
    The group data is supplied by the popup so every group uses the same markup.
--}}
<div class="enquiry-service-category">
    <h2 class="accordion-header">
        <button
            class="accordion-button collapsed enquiry-service-toggle"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $id }}"
            aria-expanded="false"
            aria-controls="{{ $id }}"
        >
            <span class="enquiry-service-category-left">
                <span class="enquiry-service-category-icon">
                    <i class="fa-solid {{ $icon }}"></i>
                </span>

                <span>
                    <strong>
                        {{ $title }}
                    </strong>

                    <small>
                        {{ $subtitle }}
                    </small>
                </span>
            </span>
        </button>
    </h2>

    <div
        id="{{ $id }}"
        class="accordion-collapse collapse"
        data-bs-parent="#enquiryServiceAccordion"
    >
        <div class="enquiry-service-list">
            @foreach ($services as $service)
                <label class="enquiry-service-option">
                    <input
                        type="radio"
                        name="service"
                        value="{{ $service }}" @required($service === $requiredOption)
                    >

                    <span>
                        {{ $service }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>
