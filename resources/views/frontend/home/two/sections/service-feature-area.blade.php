<div class="service-area-1 space bg-theme">
    <div class="service-img-1-1 shape-mockup wow img-custom-anim-left" data-wow-duration="1.5s" data-wow-delay="0.2s"
        data-left="0" data-top="-100px" data-bottom="140px">
        <img src="{{ asset($servicefeatureSection?->global_content?->image) }}" alt="img">
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-end">
            <div class="col-lg-6">
                <div class="about-content-wrap">
                    <div class="title-area mb-0">
                        <h2 class="sec-title">{{$servicefeatureSection?->content?->title}}</h2>
                        <div class="sec-text mt-35 mb-40">
                            {!! clean(processText($servicefeatureSection?->content?->sub_title)) !!}
                        </div>
                        <div class="skill-feature">
                            <h3 class="skill-feature_title">{{$servicefeatureSection?->content?->skill_title_one}}</h3>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{$servicefeatureSection?->global_content?->skill_percentage_one}}%;">
                                </div>
                                <div class="progress-value"><span class="counter-number">{{$servicefeatureSection?->global_content?->skill_percentage_one}}</span>%</div>
                            </div>
                        </div>
                        <div class="skill-feature">
                            <h3 class="skill-feature_title">{{$servicefeatureSection?->content?->skill_title_two}}</h3>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{$servicefeatureSection?->global_content?->skill_percentage_two}}%;">
                                </div>
                                <div class="progress-value"><span class="counter-number">{{$servicefeatureSection?->global_content?->skill_percentage_two}}</span>%</div>
                            </div>
                        </div>
                        <div class="skill-feature">
                            <h3 class="skill-feature_title">{{$servicefeatureSection?->content?->skill_title_three}}</h3>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{$servicefeatureSection?->global_content?->skill_percentage_three}}%;">
                                </div>
                                <div class="progress-value"><span class="counter-number">{{$servicefeatureSection?->global_content?->skill_percentage_three}}</span>%</div>
                            </div>
                        </div>
                        <div class="skill-feature">
                            <h3 class="skill-feature_title">{{$servicefeatureSection?->content?->skill_title_four}}</h3>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{$servicefeatureSection?->global_content?->skill_percentage_four}}%;">
                                </div>
                                <div class="progress-value"><span class="counter-number">{{$servicefeatureSection?->global_content?->skill_percentage_four}}</span>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>