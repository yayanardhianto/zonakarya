@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['portfolio_page']['seo_title'])
@section('meta_description', $seo_setting['portfolio_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->portfolio_page_breadcrumb_image" :title="__('Portfolio')" />

    <!-- Main Area -->
    <div class="portfolio-area-1 space overflow-hidden">
        <div class="container">
            <div class="row justify-content-between masonary-active">
                @php
                    $col_sizes = ['6', '6', '7', '5'];
                @endphp
                @forelse ($projects as $index => $project)
                    @php
                        $col_size = $col_sizes[$index % count($col_sizes)];
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
                @empty
                    <x-data-not-found />
                @endforelse
            </div>
            @if ($projects->hasPages())
                <div class="btn-wrap justify-content-center mt-60">
                    {{ $projects->onEachSide(0)->links('frontend.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
