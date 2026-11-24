{{--
    =========================================================================
    UNIFIED ENQUIRY FORM
    =========================================================================
    Every public form (popup, contact page, home page) uses this component so
    the fields, service list and validation stay identical everywhere.

    variant:
      enquiry  -> popup (default)
      contact  -> contact page
      home     -> home page

    The visual skin changes per variant; the fields never do.
    =========================================================================
--}}

@props([
    'idPrefix' => 'enquiry',
    'sourcePage' => 'popup',
    'variant' => 'enquiry',
    'formClass' => '',
    'selectedService' => null,
    'services' => null,
])

@php
    $catalogue = $services ?? \App\Models\Service::query()
        ->visible()
        ->ordered()
        ->with('category')
        ->get();

    $serviceGroups = $catalogue
        ->groupBy(fn (\App\Models\Service $service): string => $service->category?->name ?: 'Other Services')
        ->map(fn ($group): array => $group->pluck('name')->all())
        ->all();

    $eventTypes = [
        'Wedding',
        'Engagement',
        'Haldi Ceremony',
        'Mehendi Ceremony',
        'Chunni Ceremony',
        'Birthday',
        'Anniversary',
        'Family Function',
        'Festival',
        'Corporate Event',
        'Other Event',
    ];

    $budgets = [
        'Under ₹10,000',
        '₹10,000 – ₹25,000',
        '₹25,000 – ₹50,000',
        '₹50,000 – ₹1,00,000',
        'Above ₹1,00,000',
        'Not decided yet',
    ];

    $fields = [
        [
            'name' => 'name',
            'label' => 'Your Name',
            'type' => 'text',
            'col' => 'col-md-6',
            'icon' => 'fa-regular fa-user',
            'placeholder' => 'Enter your name',
            'autocomplete' => 'name',
            'required' => true,
        ],
        [
            'name' => 'phone',
            'label' => 'Mobile Number',
            'type' => 'tel',
            'col' => 'col-md-6',
            'icon' => 'fa-solid fa-phone',
            'placeholder' => '+91 00000 00000',
            'autocomplete' => 'tel',
            'inputmode' => 'tel',
            'maxlength' => 20,
            'required' => true,
        ],
        [
            'name' => 'email',
            'label' => 'Email Address',
            'type' => 'email',
            'col' => 'col-md-6',
            'icon' => 'fa-regular fa-envelope',
            'placeholder' => 'Enter email address',
            'autocomplete' => 'email',
            'optional' => true,
        ],
        [
            'name' => 'city',
            'label' => 'City / Town',
            'type' => 'text',
            'col' => 'col-md-6',
            'icon' => 'fa-solid fa-city',
            'placeholder' => 'Where is the event?',
            'autocomplete' => 'address-level2',
            'required' => true,
        ],
        [
            'name' => 'service',
            'label' => 'Decoration Service',
            'type' => 'select',
            'col' => 'col-12',
            'icon' => 'fa-solid fa-wand-magic-sparkles',
            'placeholder' => 'Select decoration service',
            'groups' => $serviceGroups,
            'required' => true,
        ],
        [
            'name' => 'event_type',
            'label' => 'Event Type',
            'type' => 'select',
            'col' => 'col-md-6',
            'icon' => 'fa-solid fa-champagne-glasses',
            'placeholder' => 'Select event type',
            'options' => $eventTypes,
            'required' => true,
        ],
        [
            'name' => 'event_date',
            'label' => 'Event Date',
            'type' => 'date',
            'col' => 'col-md-6',
            'icon' => 'fa-regular fa-calendar-days',
            'required' => true,
        ],
        [
            'name' => 'event_time',
            'label' => 'Event Time',
            'type' => 'time',
            'col' => 'col-md-6',
            'icon' => 'fa-regular fa-clock',
            'required' => true,
        ],
        [
            'name' => 'budget',
            'label' => 'Budget',
            'type' => 'select',
            'col' => 'col-md-6',
            'icon' => 'fa-solid fa-indian-rupee-sign',
            'placeholder' => 'Select budget range',
            'options' => $budgets,
            'optional' => true,
        ],
        [
            'name' => 'event_address',
            'label' => 'Event Venue / Address',
            'type' => 'text',
            'col' => 'col-12',
            'icon' => 'fa-solid fa-location-dot',
            'placeholder' => 'Enter event venue or complete address',
            'autocomplete' => 'street-address',
            'required' => true,
        ],
        [
            'name' => 'message',
            'label' => 'Additional Requirements',
            'type' => 'textarea',
            'col' => 'col-12',
            'icon' => 'fa-regular fa-message',
            'placeholder' => 'Tell us about your theme, colours, venue or any special requirement',
            'rows' => 3,
            'optional' => true,
        ],
    ];

    $fieldClass = match ($variant) {
        'contact' => 'contact-field',
        'home' => 'form-field',
        default => 'enquiry-field',
    };

    $wrapClass = match ($variant) {
        'contact' => 'contact-input-wrap',
        'home' => null,
        default => 'enquiry-input-wrap',
    };

    $textareaWrapClass = $variant === 'contact' ? 'contact-textarea-wrap' : '';

    $inputClass = $variant === 'home' ? 'form-control' : '';
    $selectClass = $variant === 'home' ? 'form-select' : '';

    $buttonClass = match ($variant) {
        'contact' => 'contact-submit-btn',
        'home' => 'contact-submit',
        default => 'enquiry-submit-btn',
    };
@endphp

<form
    action="{{ route('enquiry.store') }}"
    method="POST"
    class="{{ $formClass }}"
>
    @csrf

    <input type="hidden" name="source_page" value="{{ $sourcePage }}">
    <input type="hidden" name="source_url" value="{{ url()->current() }}">

    {{-- Honeypot: real visitors never fill this. --}}
    <input
        type="text"
        name="website"
        value=""
        tabindex="-1"
        autocomplete="off"
        style="position:absolute;left:-9999px;"
        aria-hidden="true"
    >

    <div class="row g-3">

        @foreach ($fields as $field)

            @php
                $id = $idPrefix.'-'.$field['name'];
                $hasError = $errors->has($field['name']);
                $old = old($field['name']);
            @endphp

            <div class="{{ $field['col'] }}">

                <div class="{{ $fieldClass }} @if ($hasError) has-error @endif">

                    <label for="{{ $id }}">
                        {{ $field['label'] }}

                        @if (! empty($field['optional']))
                            <span>(Optional)</span>
                        @endif
                    </label>

                    @if ($wrapClass)
                        <div class="{{ $wrapClass }} {{ $field['type'] === 'textarea' ? $textareaWrapClass : '' }}">
                            <i class="{{ $field['icon'] }}" aria-hidden="true"></i>
                    @endif

                    @if ($field['type'] === 'select')

                        <select
                            id="{{ $id }}"
                            name="{{ $field['name'] }}"
                            class="{{ $selectClass }}"
                            @if (! empty($field['required'])) required @endif
                        >
                            <option value="" @selected(blank($old))>
                                {{ $field['placeholder'] ?? 'Select an option' }}
                            </option>

                            @if (! empty($field['groups']))
                                @foreach ($field['groups'] as $groupLabel => $groupOptions)
                                    <optgroup label="{{ $groupLabel }}">
                                        @foreach ($groupOptions as $option)
                                            <option
                                                value="{{ $option }}"
                                                @selected((string) $old === (string) $option || (blank($old) && (string) $selectedService === (string) $option))
                                            >
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @else
                                @foreach ($field['options'] ?? [] as $option)
                                    <option
                                        value="{{ $option }}"
                                        @selected((string) $old === (string) $option)
                                    >
                                        {{ $option }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                    @elseif ($field['type'] === 'textarea')

                        <textarea
                            id="{{ $id }}"
                            name="{{ $field['name'] }}"
                            class="{{ $inputClass }}"
                            rows="{{ $field['rows'] ?? 4 }}"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                        >{{ $old }}</textarea>

                    @else

                        <input
                            type="{{ $field['type'] }}"
                            id="{{ $id }}"
                            name="{{ $field['name'] }}"
                            class="{{ $inputClass }}"
                            value="{{ $old }}"
                            @if (! empty($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                            @if (! empty($field['autocomplete'])) autocomplete="{{ $field['autocomplete'] }}" @endif
                            @if (! empty($field['inputmode'])) inputmode="{{ $field['inputmode'] }}" @endif
                            @if (! empty($field['maxlength'])) maxlength="{{ $field['maxlength'] }}" @endif
                            @if (! empty($field['required'])) required @endif
                        >

                    @endif

                    @if ($wrapClass)
                        </div>
                    @endif

                    @error($field['name'])
                        <small class="enquiry-field-error">{{ $message }}</small>
                    @enderror

                </div>
            </div>

        @endforeach

        <div class="col-12">
            <button type="submit" class="{{ $buttonClass }}">
                <span>
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    Send My Enquiry
                </span>

                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </button>
        </div>

    </div>
</form>
