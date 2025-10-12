@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- hero-area -->
        @include('frontend.home.three.sections.hero-area')
        <!-- hero-area-end -->
    @endif
    @if ($sectionSetting?->counter_section)
        <!-- counter-area -->
        @include('frontend.home.three.sections.counter-area')
        <!-- counter-area-end -->
    @endif
    @if ($sectionSetting?->choose_us_section)
        <!-- choose-us-area -->
        @include('frontend.home.three.sections.choose-us-area')
        <!-- choose-us-area-end -->
    @endif
    @if ($sectionSetting?->project_section)
        <!-- project-area -->
        @include('frontend.home.three.sections.project-area')
        <!-- project-area-end -->
    @endif
    @if ($sectionSetting?->service_section)
        <!-- service-area -->
        @include('frontend.home.three.sections.service-area')
        <!-- service-area-end -->
    @endif

    @if ($sectionSetting?->contact_us_section)
        <!-- brand-area -->
        @include('frontend.home.three.sections.contact-us-area')
        <!-- brand-area-end -->
    @endif

    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.three.sections.blog-area')
        <!-- blog-area-end -->
    @endif

    @if ($sectionSetting?->call_to_action_section)
        <!-- cta-area -->
        @include('frontend.home.three.sections.cta-area')
        <!-- cta-area-end -->
    @endif
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.three')
@endsection
