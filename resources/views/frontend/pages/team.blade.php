@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['team_page']['seo_title'])
@section('meta_description', $seo_setting['team_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->team_page_breadcrumb_image" :title="__('Team')" />

    <!-- Main Area -->
    <div class="team-area-1 space overflow-hidden">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @forelse ($teams as $team)
                    <div class="col-lg-3 col-md-6">
                        <div class="team-card">
                            <div class="team-card_img">
                                <img src="{{ $team?->image }}" alt="{{ $team?->name }}">
                            </div>
                            <div class="team-card_content">
                                <h3 class="team-card_title"><a
                                        href="{{ route('single.team', $team?->slug) }}">{{ $team?->name }}</a></h3>
                                <span class="team-card_desig">{{ $team?->designation }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <x-data-not-found />
                @endforelse
            </div>
            @if ($teams->hasPages())
                <div class="btn-wrap justify-content-center mt-60">
                    {{ $teams->onEachSide(0)->links('frontend.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Testimonial Area -->
    <div class="testimonial-area-1 space bg-theme">
        <div class="testimonial-img-1-1 shape-mockup wow img-custom-anim-right" data-wow-duration="1.5s"
            data-wow-delay="0.2s" data-right="0" data-top="-100px" data-bottom="140px">
            <img src="{{ asset($testimonialSection?->global_content?->image) }}" alt="{{ __('Testimonials') }}">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="title-area">
                        <h2 class="sec-title">{{ __('Testimonials') }}</h2>
                    </div>
                    <div class="quote-icon">
                        <img src="{{ asset('frontend/images/quote.svg') }}" alt="quote">
                    </div>
                    <div class="row global-carousel testi-slider1" data-slide-show="1" data-dots="true" data-xl-dots="true"
                        data-ml-dots="true">
                        @foreach ($testimonials as $testimonial)
                            <div class="col-lg-4">
                                <div class="testi-box ">
                                    <p class="testi-box_text">“{{ $testimonial?->comment }}”</p>
                                    <div class="testi-box_profile">
                                        <h4 class="testi-box_name">{{ $testimonial?->name }}</h4>
                                        <span class="testi-box_desig">{{ $testimonial?->designation }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
