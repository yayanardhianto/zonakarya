<div class="project-area-4 space-bottom overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="title-area text-center">
                    <h2 class="sec-title">{{ __('Discover Our Selected Projects') }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach ($projects->take(4) as $index => $project)
                <div class="col-md-6">
                    <a href="{{ route('single.portfolio', $project?->slug) }}" class="portfolio-wrap style3">
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
        <div class="btn-wrap mt-50 justify-content-center">
            <a href="{{ route('portfolios') }}" class="btn">
                <span class="link-effect">
                    <span class="effect-1">{{ __('View All') }}</span>
                    <span class="effect-1">{{ __('View All') }}</span>
                </span>
            </a>
        </div>
    </div>
</div>
