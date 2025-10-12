@extends('frontend.layouts.master02')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.custom')
@endsection

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- hero-area -->
        @include('frontend.home.custom.sections.hero-area')
        <!-- hero-area-end -->
    @endif
    
    @if ($sectionSetting?->about_section)
        <!-- about-area -->
        @include('frontend.home.custom.sections.about-area')
        <!-- about-area-end -->
    @endif
    
    @if ($sectionSetting?->service_section)
        <!-- service-area -->
        @include('frontend.home.custom.sections.service-area')
        <!-- service-area-end -->
    @endif
    
    @if ($sectionSetting?->project_section)
        <!-- project-area -->
        @include('frontend.home.custom.sections.project-area')
        <!-- project-area-end -->
    @endif
    
    @if ($sectionSetting?->testimonial_section)
        <!-- testimonial-area -->
        @include('frontend.home.custom.sections.testimonial-area')
        <!-- testimonial-area-end -->
    @endif
    
    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.custom.sections.blog-area')
        <!-- blog-area-end -->
    @endif
    
    @if ($sectionSetting?->call_to_action_section)
        <!-- cta-area -->
        @include('frontend.home.custom.sections.cta-area')
        <!-- cta-area-end -->
    @endif
@endsection

@section('footer')
    @include('frontend.layouts.footer-layout.custom')
@endsection
