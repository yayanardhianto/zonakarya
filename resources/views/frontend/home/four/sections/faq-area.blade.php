<div class="faq-area-2 space-bottom overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="accordion-area accordion" id="faqAccordion">
                    @foreach ($faqs as $index => $faq)
                            <div class="accordion-card style2 {{ $index == 0 ? 'active' : '' }}">
                                <div class="accordion-header" id="collapse-item-{{ $index + 1 }}">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index + 1 }}"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse-{{ $index + 1 }}">{{ $faq?->question }}</button>
                                </div>
                                <div id="collapse-{{ $index + 1 }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
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