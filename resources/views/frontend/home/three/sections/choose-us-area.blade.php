<div class="why-area-1 space bg-theme">
    <div class="why-img-1-1 shape-mockup wow img-custom-anim-right" data-wow-duration="1.5s" data-wow-delay="0.2s"
        data-right="0" data-top="-100px" data-bottom="140px">
        <img src="{{asset($chooseUsSection?->global_content?->image)}}" alt="{{$chooseUsSection?->content?->title}}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="title-area mb-45">
                    <h2 class="sec-title choose-us-title">{!! clean(processText($chooseUsSection?->content?->title)) !!}</h2>
                </div>
                {!! clean(processText($chooseUsSection?->content?->sub_title)) !!}
            </div>
        </div>

    </div>
</div>