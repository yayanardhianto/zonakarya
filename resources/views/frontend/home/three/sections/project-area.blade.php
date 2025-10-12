<div class="award-area-1 space overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <ul class="award-wrap-area">
                    @foreach ($projects as $index => $project)
                        <li class="single-award-list style2 tg-img-reveal-item" data-fx="1"
                            data-img="{{ asset($project?->image) }}">
                            <span class="award-year">{{ date('Y', strtotime($project?->project_date)) }}</span>
                            <div class="award-details">
                                <h4><a
                                        href="{{ route('single.portfolio', $project?->slug) }}">{{ $project?->title }}</a>
                                </h4>
                                <div class="award-meta">
                                    <a href="{{ route('single.service', $project?->service?->slug) }}">{{ $project?->service?->title }}</a>
                                </div>
                            </div>
                            <span class="award-tag">{{ $project?->project_category }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="btn-wrap mt-50 justify-content-center">
                    <a href="{{route('portfolios')}}" class="btn">
                        <span class="link-effect">
                            <span class="effect-1">{{__('View All Projects')}}</span>
                            <span class="effect-1">{{__('View All Projects')}}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
