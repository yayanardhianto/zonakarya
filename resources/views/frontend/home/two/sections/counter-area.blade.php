<div class="counter-area-1 space overflow-hidden">
    <div class="container">
        <div class="row gy-40 align-items-center justify-content-lg-between justify-content-center">
            <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                <div class="counter-card">
                    <h3 class="counter-card_number">
                        <span class="counter-number">{{$counterSection?->global_content?->year_experience_count}}</span>+
                    </h3>
                    <h4 class="counter-card_title">{{$counterSection?->content?->year_experience_title}}</h4>
                    <div class="counter-card_text">
                        {!! clean(processText($counterSection?->content?->year_experience_sub_title)) !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                <div class="counter-card">
                    <h3 class="counter-card_number">
                        <span class="counter-number">{{$counterSection?->global_content?->project_count}}</span>+
                    </h3>
                    <h4 class="counter-card_title">{{$counterSection?->content?->project_title}}</h4>
                    <div class="counter-card_text">
                        {!! clean(processText($counterSection?->content?->project_sub_title)) !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                <div class="counter-card">
                    <h3 class="counter-card_number">
                        <span class="counter-number">{{$counterSection?->global_content?->customer_count}}</span>+
                    </h3>
                    <h4 class="counter-card_title">{{$counterSection?->content?->customer_title}}</h4>
                    <div class="counter-card_text">
                        {!! clean(processText($counterSection?->content?->customer_sub_title)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>