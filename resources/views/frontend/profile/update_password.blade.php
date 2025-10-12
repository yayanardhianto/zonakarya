@extends('frontend.layouts.master')

@section('meta_title', __('Change your password') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Change your password')" />
    
    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 wow fadeInUp">
                    <div class="wsus__dashboard_main_contant">
                        <h4>{{__('Change your password')}}</h4>
                        <form  method="POST" action="{{ route('user.update-password') }}" class="wsus__dashboard_change_password  wow fadeInUp">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6">
                                    <input type="password" placeholder="{{__('Current Password')}}" name="current_password">
                                </div>
                                <div class="col-xl-6">
                                    <input type="password" name="password" placeholder="{{ __('New Password') }}">
                                </div>
                                <div class="col-xl-12">
                                    <input type="password" name="password_confirmation"
                                        placeholder="{{ __('Confirm Password') }}">
                                </div>
                                <div class="col-xl-12">
                                    <ul class="d-flex flex-wrap">
                                        <li>
                                            <a href="{{route('user.dashboard')}}" class="btn">
                                                <span class="link-effect">
                                                    <span class="effect-1">{{__('Cancel')}}</span>
                                                    <span class="effect-1">{{__('Cancel')}}</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <button class="btn style2" type="submit">
                                                <span class="link-effect">
                                                    <span class="effect-1">{{__('Update')}}</span>
                                                    <span class="effect-1">{{__('Update')}}</span>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
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
