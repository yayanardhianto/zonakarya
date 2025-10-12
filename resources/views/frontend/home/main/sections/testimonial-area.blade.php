<div class="testimonial-area-1 space bg-theme">
    <div class="testimonial-img-1-1 shape-mockup wow img-custom-anim-right" data-wow-duration="1.5s"
        data-wow-delay="0.2s" data-right="0" data-top="-100px" data-bottom="140px">
        <img src="{{ asset($testimonialSection?->global_content?->image) }}" alt="{{__('Testimonials')}}">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="title-area ">
                    <h2 class="sec-title">{{__('Testimonials')}}</h2>
                </div>
                <div class="quote-icon ">
                    <img src="{{ asset('frontend/images/quote.svg') }}" alt="quote">
                </div>
                <div class="row global-carousel testi-slider1" data-slide-show="1" data-dots="true"
                    data-xl-dots="true" data-ml-dots="true">
                    @foreach ($testimonials as $testimonial)
                    <div class="col-lg-4">
                        <div class="testi-box ">
                            <p class="testi-box_text">“{{$testimonial?->comment}}”</p>
                            <div class="testi-box_profile">
                                <p class="testi-box_name name">{{$testimonial?->name}}</p>
                                <span class="testi-box_desig">{{$testimonial?->designation}}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>