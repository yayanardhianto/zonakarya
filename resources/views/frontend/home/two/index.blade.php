@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.two')
@endsection

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- hero-area -->
        @include('frontend.home.two.sections.hero-area')
        <!-- hero-area-end -->
    @endif
    @if ($sectionSetting?->counter_section)
        <!-- counter-area -->
        @include('frontend.home.two.sections.counter-area')
        <!-- counter-area-end -->
    @endif
    @if ($sectionSetting?->about_section)
        <!-- counter-area -->
        @include('frontend.home.two.sections.about-area')
        <!-- counter-area-end -->
    @endif
    @if ($sectionSetting?->service_section)
        <!-- service-area -->
        @include('frontend.home.two.sections.service-area')
        <!-- service-area-end -->
    @endif

    @if ($sectionSetting?->brands_section)
        <!-- brand-area -->
        @include('frontend.home.two.sections.brand-area')
        <!-- brand-area-end -->
    @endif

    <!-- @if ($sectionSetting?->service_feature_section) -->
        <!-- service feature-area -->
        <!-- @include('frontend.home.two.sections.service-feature-area') -->
        <!-- service feature-area-end -->
    <!-- @endif -->
    <!-- @if ($sectionSetting?->project_section) -->
        <!-- project-area -->
        <!-- @include('frontend.home.two.sections.project-area') -->
        <!-- project-area-end -->
    <!-- @endif -->
    <!-- @if ($sectionSetting?->award_section) -->
        <!-- award-area -->
        <!-- @include('frontend.home.two.sections.award-area') -->
        <!-- award-area-end -->
    <!-- @endif -->
    @if ($sectionSetting?->banner_section)
        <!-- banner-area -->
        @include('frontend.home.two.sections.banner-area')
        <!-- banner-area-end -->
    @endif
    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.two.sections.blog-area')
        <!-- blog-area-end -->
    @endif
    @if ($sectionSetting?->contact_us_section)
        <!-- brand-area -->
        @include('frontend.home.three.sections.contact-us-area')
        <!-- brand-area-end -->
    @endif

    <!-- @if ($sectionSetting?->call_to_action_section) -->
        <!-- cta-area -->
        <!-- @include('frontend.home.two.sections.cta-area') -->
        <!-- cta-area-end -->
    <!-- @endif -->
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
