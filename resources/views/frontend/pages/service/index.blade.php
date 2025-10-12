@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['service_page']['seo_title'])
@section('meta_description', $seo_setting['service_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->service_page_breadcrumb_image" :title="__('Service')" />

    <!-- Main Area -->
    <div class="feature-area-1 space">
        <div class="container">
            <div class="row gy-4 align-items-center justify-content-center">
                @forelse ($services as $service)
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
                @empty
                    <x-data-not-found />
                @endforelse
            </div>
            @if ($services->hasPages())
                <div class="btn-wrap justify-content-center mt-60">
                    {{ $services->onEachSide(0)->links('frontend.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Video Area -->
    <div class="video-area-1 overflow-hidden">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="video-wrap">
                        <div class="jarallax" data-bg-src="{{ asset($bannerSection?->global_content?->image) }}">
                        </div>
                        <a href="{{ $bannerSection?->global_content?->video_url }}"
                            class="play-btn circle-btn btn gsap-magnetic popup-video background-image text-uppercase">{{ __('Play Video') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
