<div class="portfolio-area-1 space overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-9">
                <div class="title-area text-center">
                    <h2 class="sec-title">{{__('Discover Our Selected Projects')}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid p-0">
        <div class="row global-carousel gx-60 portfolio-slider" data-slide-show="1" data-center-mode="true"
            data-xl-center-mode="true" data-ml-center-mode="true" data-lg-center-mode="true"
            data-center-padding="600px" data-xl-center-padding="400px" data-ml-center-padding="400px"
            data-lg-center-padding="300px" data-dots="true" data-xl-dots="true" data-ml-dots="true">
            @foreach ($projects as $index => $project)
            <div class="col-lg-4">
                <a href="{{ route('single.portfolio', $project?->slug) }}" class="portfolio-wrap style2">
                    <div class="portfolio-thumb">
                        <img src="{{ asset($project?->image) }}" alt="{{ $project?->title }}">
                    </div>
                    <div class="portfolio-details">
                        <ul class="portfolio-meta">
                            <li>{{ $project?->project_category }}</li>
                        </ul>
                        <h3 class="portfolio-title">{{ $project?->title }}</h3>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>