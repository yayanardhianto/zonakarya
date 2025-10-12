<div class="portfolio-area-1 space-bottom overflow-hidden" data-bg-src="{{ asset('frontend/images/bg/portfolio-1-bg.png') }}">
    <div class="container">
        <div class="row justify-content-between masonary-active">
            @foreach ($projects->take(4) as $index => $project)
                @php
                    $col_sizes = ['6', '6', '7', '5'];
                    $col_size = $col_sizes[$index];
                @endphp
                <div class="col-lg-{{ $col_size }} filter-item">
                    <div class="portfolio-wrap mt-140 {{ $index == 0 ? 'mt-lg-140' : ($index == 1 ? 'mt-lg-0' : '') }}">
                        <div class="portfolio-thumb wow img-custom-anim-top" data-wow-duration="1.5s"
                            data-wow-delay="0.2s">
                            <a href="{{ route('single.portfolio', $project?->slug) }}">
                                <img src="{{ asset($project?->image) }}" alt="{{ $project?->title }}">
                            </a>
                        </div>
                        <div class="portfolio-details">
                            <ul class="portfolio-meta">
                                <li><a href="javascript:;">{{ $project?->project_category }}</a></li>
                            </ul>
                            <h3 class="portfolio-title"><a
                                    href="{{ route('single.portfolio', $project?->slug) }}">{{ $project?->title }}</a>
                            </h3>
                            <a href="{{ route('single.portfolio', $project?->slug) }}" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ __('View Project') }}</span>
                                    <span class="effect-1">{{ __('View Project') }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-auto filter-item">
                <div class="btn-wrap mt-140">
                    <a class="circle-btn btn gsap-magnetic mx-lg-5" href="{{ route('portfolios') }}">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('View All') }}</span>
                            <span class="effect-1">{{ __('View All') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>