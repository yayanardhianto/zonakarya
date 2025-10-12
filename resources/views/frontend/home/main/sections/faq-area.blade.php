<div class="faq-area-1 space overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="title-area text-center ">
                    <h2 class="sec-title">{{ __('What We Can Do for Our Clients') }}</h2>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="accordion-area accordion" id="faqAccordion">
                    @foreach ($faqs as $index => $faq)
                        <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
                            <div class="accordion-header" id="collapse-item-{{ $index + 1 }}">
                                <button class="accordion-button {{ $index == 0 ? '' : 'collapsed'}}" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $index + 1 }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $index + 1 }}">
                                    <span class="faq-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span> {{ $faq?->question }}
                                </button>
                            </div>
                            <div id="collapse-{{ $index + 1 }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                aria-labelledby="collapse-item-{{ $index + 1 }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="faq-text">{{ $faq?->answer }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
