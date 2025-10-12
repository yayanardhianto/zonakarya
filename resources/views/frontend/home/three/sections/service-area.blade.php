<div class="feature-area-1 space-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="title-area text-center">
                    <h2 class="sec-title">{{ __('What We Can Do for Our Clients') }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4 align-items-center justify-content-center">
            @foreach ($services->take(3) as $service)
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <img src="{{ asset($service?->icon) }}" alt="{{ $service?->title }}">
                        </div>
                        <h4 class="feature-card-title">
                            <a href="{{ route('single.service', $service?->slug) }}">{{ $service?->title }}</a>
                        </h4>
                        <p class="feature-card-text">{{ $service?->short_description }}</p>
                        <a href="{{ route('single.service', $service?->slug) }}" class="link-btn">
                            <span class="link-effect">
                                <span class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                                <span class="effect-1">{{ $service?->btn_text ?? 'View Details' }}</span>
                            </span>
                            <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="arrow-left">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>