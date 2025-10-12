@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.one')
@endsection

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- hero-area -->
        @include('frontend.home.four.sections.hero-area')
        <!-- hero-area-end -->
    @endif

    @if ($sectionSetting?->service_section)
        <!-- service-area -->
        @include('frontend.home.four.sections.service-area')
        <!-- service-area-end -->
    @endif

    @if ($sectionSetting?->brands_section)
        <!-- brand-area -->
        @include('frontend.home.two.sections.brand-area')
        <!-- brand-area-end -->
    @endif

    @if ($sectionSetting?->project_section)
        <!-- project-area -->
        @include('frontend.home.four.sections.project-area')
        <!-- project-area-end -->
    @endif

    @if ($sectionSetting?->choose_us_section)
        <!-- choose-us-area -->
        @include('frontend.home.four.sections.choose-us-area')
        <!-- choose-us-area-end -->
    @endif
    @if ($sectionSetting?->marquee_section)
        <!-- marquee-area -->
        @include('frontend.partials.marquee')
        <!-- marquee-area-end -->
    @endif


    @if ($sectionSetting?->faq_section)
        <!-- faq-area -->
        @include('frontend.home.four.sections.faq-area')
        <!-- faq-area-end -->
    @endif
    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.four.sections.blog-area')
        <!-- blog-area-end -->
    @endif
    @if ($sectionSetting?->call_to_action_section)
        <!-- cta-area -->
        @include('frontend.home.four.sections.cta-area')
        <!-- cta-area-end -->
    @endif
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.four')
@endsection
