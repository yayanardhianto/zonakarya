<div class="hero-wrapper hero-4" id="hero">
    <div class="hero-4-thumb img-custom-anim-left wow shape-mockup" data-left="0">
        <img class="w-100" src="{{ asset($hero?->global_content?->image) }}" alt="img">
    </div>
    <div class="bg-theme">
        <div class="container">
            <div class="hero-style4">
                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <h1 class="hero-title wow img-custom-anim-right">{!! processText($hero?->content?->title) !!}</h1>
                        <p class="hero-text wow img-custom-anim-right">{!! processText($hero?->content?->sub_title) !!}</p>
                        <div class="btn-group fade_right">
                            <a href="{{ $hero?->global_content?->action_button_url }}"
                                class="btn wow img-custom-anim-right">
                                <span class="link-effect text-uppercase">
                                    <span class="effect-1">{{ $hero?->content?->action_button_text }}</span>
                                    <span class="effect-1">{{ $hero?->content?->action_button_text }}</span>
                                </span>
                            </a>
                        </div>
                        <div class="hero-thumb-group img-custom-anim-right wow">
                            <img class="img1" src="{{ asset($hero?->global_content?->image_two) }}" alt="img">
                            <p>{{__('More than')}} {{ $hero?->content?->total_customers }} {{__('trusted customers')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>