@extends('frontend.layouts.master')

@section('meta_title', __('Sign up') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <div class="auth-main-area">
        <!--  Sign Up Area -->
        <section class="signin__area space">
            <div class="container">
                <div class="row justify-content-center wow fadeInUp">
                    <div class="col-xxl-5 col-md-9 col-lg-7 col-xl-6">
                        <div class="wsus__sign_in_form mt_80 pb_115 xs_pb_95">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <h3>{{ __('Create an account') }}</h3>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Name') }}*</label>
                                    <input type="text" name="name" placeholder="{{ __('Name') }}">
                                </div>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Email') }}*</label>
                                    <input type="email" name="email" placeholder="{{ __('Email') }}">
                                </div>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Password') }}*</label>
                                    <input type="password" name="password" placeholder="{{ __('Password') }}">
                                </div>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Confirm Password') }}*</label>
                                    <input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                                </div>
                                @if ($setting->recaptcha_status == 'active')
                                    <div class="form-group mt-3 d-flex justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                    </div>
                                @endif
    
    
                                <button class="btn mt-4 w-100">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Sign up') }}</span>
                                        <span class="effect-1">{{ __('Sign up') }}</span>
                                    </span>
                                </button>
    
                            </form>
                            @if (enum_exists('App\Enums\SocialiteDriverType'))
                                @php
                                    $socialiteEnum = 'App\Enums\SocialiteDriverType';
                                    $icons = $socialiteEnum::getIcons();
                                    $case = $socialiteEnum::GOOGLE;
                                    $driverName = $case->value . '_login_status';
                                @endphp
                                @if ($setting?->$driverName == 'active')
                                    <p class="or">{{ __('OR') }}</p>
                                    <ul class="wsus__another_login d-flex flex-wrap">
                                        <li>
                                            <a href="{{ route('auth.social', $case->value) }}">
                                                <span><img src="{{ asset($icons[$case->value]) }}"
                                                        alt="{{ $case->value }}" class="img-fluid"></span>
                                                {{ $case->value }}
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            @endif
                            <p class="dont_account mb-40">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign in') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
