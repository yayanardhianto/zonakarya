<div class="hero-wrapper hero-3" id="hero">
    <div class="container">
        <div class="hero-style3">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="hero-title wow img-custom-anim-left">{{$hero?->content?->title}}</h1>
                    <div class="hero-3-thumb wow img-custom-anim-top">
                        <img class="w-100" src="{{ asset($hero?->global_content?->image) }}" alt="img">
                    </div>
                    <h1 class="hero-title text-end wow img-custom-anim-right">{{$hero?->content?->title_two}}</h1>
                </div>
                <div class="col-lg-6 offset-lg-6">
                    <p class="hero-text wow img-custom-anim-right">{!! clean(processText($hero?->content?->sub_title)) !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

