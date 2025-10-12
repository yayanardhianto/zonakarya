@extends('frontend.layouts.master')

@section('meta_title', __('Your Review') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Your Review')" />

    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 ">
                    <div class="wsus__dashboard_main_contant ">
                        <h4>{{ __('Your Review') }}</h4>
                        <div class="wsus__dashboard_review">
                            <div class="row">
                                @forelse ($reviews as $key => $review)
                                    <div class="col-xl-12 wow fadeInUp">
                                        <div class="wsus__blog_single_comment wsus__product_review">
                                            <div class="img">
                                                <img src="{{ asset($review?->user?->image ?? $setting->default_avatar) }}" alt="{{ $review?->name }}"
                                                    class="img-fluid w-100">
                                            </div>
                                            <div class="text">
                                                <h5>{{ $review?->name }}
                                                    <span class="review_icon">
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <i
                                                                class="{{ $i < $review?->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                                                        @endfor
                                                    </span>
                                                </h5>
                                                <span class="date"> {{ formattedDate($review?->created_at) }}</span>
                                                <p>{{ $review?->review }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <x-data-not-found />
                                    </tr>
                                @endforelse
                            </div>
                        </div>

                        @if ($reviews->hasPages())
                            {{ $reviews->onEachSide(0)->links('frontend.pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
