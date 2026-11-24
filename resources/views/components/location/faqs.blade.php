{{-- =========================================================
     FAQ ACCORDION
     Yahi questions page ke FAQPage schema me bhi jaate hain,
     isliye text dono jagah same rehta hai (Google guideline).
========================================================= --}}

@props([
    'faqs',
    'tag' => null,
    'title' => null,
])

@if (! empty($faqs))

    <section class="loc-section" aria-labelledby="locFaqTitle">

        <div class="container">

            <div class="loc-section-head">

                @if (filled($tag))
                    <span class="loc-section-tag">{{ $tag }}</span>
                @endif

                @if (filled($title))
                    <h2 id="locFaqTitle">{{ $title }}</h2>
                @endif

            </div>

            <div class="row justify-content-center">

                <div class="col-lg-10">

                    <div class="accordion loc-faq" id="locFaqAcc">

                        @foreach ($faqs as $index => $faq)

                            <div class="accordion-item">

                                <h3 class="accordion-header">

                                    <button
                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#locFaq{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    >

                                        <span class="loc-faq-number">
                                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span>{{ $faq['question'] }}</span>

                                    </button>

                                </h3>

                                <div
                                    id="locFaq{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#locFaqAcc"
                                >

                                    <div class="accordion-body">
                                        {{ $faq['answer'] }}
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>

@endif
