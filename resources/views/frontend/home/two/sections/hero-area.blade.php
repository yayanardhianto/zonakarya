<div class="hero-wrapper hero-2 bg-white" id="hero">
    <div class="hero-2-thumb wow img-custom-anim-right" data-wow-duration="1.5s" data-wow-delay="0.2s">
        <img src="{{ asset($hero?->global_content?->image) }}" alt="img" style="opacity: 0.75;">
    </div>
    <div class="container">
        <div class="hero-style2">
            <div class="row">
                <div class="col-lg-9">
                    <h1 class="hero-title wow img-custom-anim-right">{{$hero?->content?->title}}</h1>
                    <h1 class="hero-title wow img-custom-anim-left">{{$hero?->content?->title_two}}</h1>
                </div>
                <div class="col-lg-10">
                    <h1 class="hero-title wow img-custom-anim-right">{{$hero?->content?->title_three}}</h1>
                </div>
                <div class="col-xxl-4 col-xl-5 col-lg-6 hero-two-sub-title wow img-custom-anim-left">
                    {!! clean(processText($hero?->content?->sub_title)) !!}
                    <div class="btn-group fade_left mt-5">
                        <a href="{{$hero?->global_content?->action_button_url}}" class="btn style2 wow img-custom-anim-left">
                            <span class="link-effect text-uppercase">
                                <span class="effect-1">{{$hero?->content?->action_button_text}}</span>
                                <span class="effect-1">{{$hero?->content?->action_button_text}}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- <div class="row justify-content-end">
                <div class="col-xxl-6 col-xl-7 col-lg-9">
                    <div class="wow img-custom-anim-right">
                        <div class="hero-contact-wrap">
                            {{$contactSection?->address}}
                        </div>
                        <div class="hero-contact-wrap">
                            <a href="tel:{{$contactSection?->phone}}">{{$contactSection?->phone}}</a>
                            <a href="mailto:{{$contactSection?->email}}">{{$contactSection?->email}}</a>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>