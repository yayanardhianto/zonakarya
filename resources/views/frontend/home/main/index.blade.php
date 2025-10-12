@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['home_page']['seo_title'])
@section('meta_description', $seo_setting['home_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.one')
@endsection

@section('contents')
    @if ($sectionSetting?->hero_section)
        <!-- hero-area -->
        @include('frontend.home.main.sections.hero-area')
        <!-- hero-area-end -->
    @endif
    @if ($sectionSetting?->about_section)
        <!-- about-area -->
        @include('frontend.home.main.sections.about-area')
        <!-- about-area-end -->
    @endif
    @if ($sectionSetting?->faq_section)
        <!-- faq-area -->
        @include('frontend.home.main.sections.faq-area')
        <!-- faq-area-end -->
    @endif
    @if ($sectionSetting?->project_section)
        <!-- project-area -->
        @include('frontend.home.main.sections.project-area')
        <!-- project-area-end -->
    @endif
    @if ($sectionSetting?->team_section)
        <!-- team-area -->
        @include('frontend.home.main.sections.team-area')
        <!-- team-area-end -->
    @endif
    @if ($sectionSetting?->testimonial_section)
        <!-- team-area -->
        @include('frontend.home.main.sections.testimonial-area')
        <!-- team-area-end -->
    @endif
    @if ($sectionSetting?->latest_blog_section)
        <!-- blog-area -->
        @include('frontend.home.main.sections.blog-area')
        <!-- blog-area-end -->
    @endif
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.one')
@endsection