@extends('frontend.layouts.master')

@section('meta_title', __('Dashboard') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Dashboard')" />

    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9">
                    <div class="wsus__dashboard_main_contant">
                        <div class="row">

                        </div>
                        <div class="wsus__profile_info">
                            <div class="wsus__profile_info_top">
                                <h4>{{ __('Personal Information') }}</h4>
                                <a href="{{ route('user.profile.edit') }}" class="btn">
                                    <span class="link-effect">
                                        <span class="effect-1">{{ __('Edit Info') }}</span>
                                        <span class="effect-1">{{ __('Edit Info') }}</span>
                                    </span>
                                </a>
                            </div>

                            <ul class="">
                                <li><span>{{ __('Name') }}:</span>{{ $user?->name }}</li>
                                <li><span>{{ __('Phone') }}:</span>{{ $user?->phone }}</li>
                                <li class="text-lowercase"><span>{{ __('Email') }}:</span>{{ $user?->email }}</li>
                                <li><span>{{ __('Gender') }}:</span>{{ $user?->gender }}</li>
                                <li><span>{{ __('Age') }}:</span>{{ $user?->age }}</li>
                                <li><span>{{ __('Country') }}:</span>{{ $user?->country?->name }}</li>
                                <li><span>{{ __('Province') }}:</span>{{ $user?->province }}</li>
                                <li><span>{{ __('City') }}:</span>{{ $user?->city }}</li>
                                <li><span>{{ __('Zip code') }}:</span>{{ $user?->zip_code }}</li>
                                <li><span>{{ __('Address') }}:</span>{{ $user?->address }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Marquee Area -->
    <!-- @include('frontend.partials.marquee') -->
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
