<div class="about-area-1 space bg-theme">
    <div class="about-img-1-1 shape-mockup img-custom-anim-left wow" data-left="0" data-top="-100px"
        data-bottom="140px" data-wow-duration="1.5s" data-wow-delay="0.1s">
        <img src="{{ asset($aboutSection?->global_content?->image) }}" alt="{{$aboutSection?->content?->title}}">
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-end">
            <div class="col-lg-6">
                <div class="overflow-hidden">
                    <div class="about-content-wrap ">
                        <div class="title-area mb-0">
                            <h2 class="sec-title">{{$aboutSection?->content?->title}}</h2>
                            <div class="sec-text mt-35">{!! clean(processText($aboutSection?->content?->description)) !!}</div>
                            <div class="btn-wrap mt-50">
                                <a href="{{ $aboutSection?->global_content?->button_url }}" class="link-btn">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ $aboutSection?->content?->button_text }}</span>
                                        <span class="effect-1">{{ $aboutSection?->content?->button_text }}</span>
                                    </span>
                                    <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="{{ $aboutSection?->content?->button_text }}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>