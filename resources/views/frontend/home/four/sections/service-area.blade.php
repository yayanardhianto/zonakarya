<div class="feature-area-1 space">
    <div class="container">
        <div class="row justify-content-xl-between justify-content-center">
            <div class="col-xl-4 col-lg-8 position-relative">
                <div class="sec_title_static">
                    <div class="sec_title_wrap">
                        <div class="title-area">
                            <h2 class="sec-title">{{ __('What We Can Do for Our Clients') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-7 col-xl-8">
                <div class="feature-static-wrap">
                    <div class="feature-static">
                        <div class="row gy-4">
                            @foreach ($services->slice(0, 2) as $service)
                                <div class="col-md-6">
                                    <div class="feature-card">
                                        <div class="feature-card-icon">
                                            <img src="{{ asset($service?->icon) }}" alt="{{ $service?->title }}">
                                        </div>
                                        <h4 class="feature-card-title">
                                            <a
                                                href="{{ route('single.service', $service?->slug) }}">{{ $service?->title }}</a>
                                        </h4>
                                        <p class="feature-card-text">{{ $service?->short_description }}</p>
                                        <a href="{{ route('single.service', $service?->slug) }}" class="link-btn">
                                            <span class="link-effect">
                                                <span
                                                    class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                                                <span
                                                    class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                                            </span>
                                            <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="arrow-left">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="feature-static">
                        <div class="row gy-4">
                            @foreach ($services->slice(2, 2) as $service)
                                <div class="col-md-6">
                                    <div class="feature-card">
                                        <div class="feature-card-icon">
                                            <img src="{{ asset($service?->icon) }}" alt="{{ $service?->title }}">
                                        </div>
                                        <h4 class="feature-card-title">
                                            <a
                                                href="{{ route('single.service', $service?->slug) }}">{{ $service?->title }}</a>
                                        </h4>
                                        <p class="feature-card-text">{{ $service?->short_description }}</p>
                                        <a href="{{ route('single.service', $service?->slug) }}" class="link-btn">
                                            <span class="link-effect">
                                                <span
                                                    class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                                                <span
                                                    class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                                            </span>
                                            <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="arrow-left">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
