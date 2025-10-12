<div class="hero-wrapper hero-1" id="hero">
    <div class="container">
        <div class="hero-style1">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="hero-title wow img-custom-anim-left" data-wow-duration="1.5s" data-wow-delay="0.1s">{{$hero?->content?->title}}</h1>

                    <h1 class="hero-title text-lg-end wow img-custom-anim-right" data-wow-duration="1.7s"
                        data-wow-delay="0.1s">{{$hero?->content?->title_two}}</h1>
                </div>
                <div class="col-lg-6 offset-lg-6">
                    <p class="hero-text wow img-custom-anim-right" data-wow-duration="1.5s" data-wow-delay="0.1s">{!! clean(processText($hero?->content?->sub_title)) !!}</p>
                    <div class="btn-group fade_right">
                        <a href="{{$hero?->global_content?->action_button_url}}" class="btn wow img-custom-anim-right">
                            <span class="link-effect text-uppercase">
                                <span class="effect-1">{{$hero?->content?->action_button_text}}</span>
                                <span class="effect-1">{{$hero?->content?->action_button_text}}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-year-tag wow img-custom-anim-left">
                <img src="{{ asset($hero?->global_content?->hero_year_image) }}" alt="{{$hero?->content?->hero_year_text}}">
                <p>{{$hero?->content?->hero_year_text}}</p>
            </div>
        </div>
    </div>
</div>