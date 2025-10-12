@extends('frontend.layouts.master')

@section('meta_title', __('Error') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
<div class="error-wrapper text-center">
    <div class="container">
        <img class="mb-50" src="{{asset('frontend/images/404.png')}}" alt="error">
        <h2>{{__('Look Like You are Lost')}}</h2>
        <p class="sec-text mb-30">{{__('The link you followed probably broken or the page has been removed')}}</p>
        <a href="{{ route('home') }}" class="link-btn">
            <span class="link-effect">
                <span class="effect-1">{{__('back to home')}}</span>
                <span class="effect-1">{{__('back to home')}}</span>
            </span>
            <img src="{{asset('frontend/images/arrow-left-top.svg')}}" alt="icon">
        </a>
    </div>
</div>
@endsection
