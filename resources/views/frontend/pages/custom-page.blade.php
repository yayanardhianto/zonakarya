@extends('frontend.layouts.master')

@section('meta_title', $customPage?->title . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- breadcrumb-area -->
    <x-breadcrumb-two :title="$customPage?->title" :links="[['url' => route('home'), 'text' => __('Home')]]" />

    <div class="project-details-page-area space">
        <div class="container">
            {!! clean(replaceImageSources($customPage?->description)) !!}
        </div>
    </div>
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
