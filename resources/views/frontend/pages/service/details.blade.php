@extends('frontend.layouts.master')

@section('meta_title', $service?->seo_title . ' || ' . $setting->app_name)
@section('meta_description', $service?->seo_description)

@push('custom_meta')
    <meta property="og:title" content="{{ $service?->seo_title }}" />
    <meta property="og:description" content="{{ $service?->seo_description }}" />
    <meta property="og:image" content="{{ asset($service?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endpush

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- breadcrumb-area -->
    <x-breadcrumb-two :title="$service?->title" :links="[['url' => route('home'), 'text' => __('Home')],['url' => route('services'), 'text' => __('Service')]]" />

    <!-- Main Area -->
    <div class="service-details-page-area space">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-12">
                    <div class="service-inner-thumb mb-80 wow img-custom-anim-top">
                        <img class="w-100" src="{{ asset($service?->image) }}" alt="{{ $service?->title }}">
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="title-area details-text mb-35">
                        <h2>{{ $service?->title }}</h2>
                        {!! clean(replaceImageSources($service?->description)) !!}
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
