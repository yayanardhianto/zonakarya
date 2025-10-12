@extends('frontend.layouts.master')

@section('meta_title', __('Sign In') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.two')
@endsection

@section('contents')
    <div class="auth-main-area">
        <!--  Sign in Area -->
        <section class="signin__area space">
            <div class="container">
                <div class="row justify-content-center wow fadeInUp">
                    <div class="col-xxl-5 col-md-9 col-lg-7 col-xl-6">
                        <div class="wsus__sign_in_form mt_80 pb_115 xs_pb_95">
                            <form method="POST" action="{{ route('user-login') }}">
                                @csrf
                                <h3>{{ __('Sign in your account') }}</h3>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Email') }}*</label>
                                    @if ('demo' == strtolower(config('app.app_mode')))
                                        <input type="email" name="email" placeholder="{{ __('Email') }}"
                                            value="user@gmail.com">
                                    @else
                                        <input type="email" name="email" placeholder="{{ __('Email') }}">
                                    @endif
                                </div>
                                <div class="wsus__sign_in_input">
                                    <label>{{ __('Password') }}*</label>
                                    @if ('demo' == strtolower(config('app.app_mode')))
                                        <input id="password" type="password" name="password"
                                            placeholder="{{ __('Password') }}" value="1234">
                                    @else
                                        <input id="password" type="password" name="password"
                                            placeholder="{{ __('Password') }}">
                                    @endif
                                </div>
                                <div
                                    class="wsus__sign_in_input d-flex flex-wrap align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox" name="remember">
                                        <label class="form-check-label" for="checkbox">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                                </div>
                                @if ($setting->recaptcha_status == 'active')
                                    <div class="form-group mt-3 d-flex justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                    </div>
                                @endif

                                <button class="btn mt-4 w-100">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Sign in') }}</span>
                                        <span class="effect-1">{{ __('Sign in') }}</span>
                                    </span>
                                </button>

                            </form>
                            @if (enum_exists('App\Enums\SocialiteDriverType'))
                                @php
                                    $socialiteEnum = 'App\Enums\SocialiteDriverType';
                                    $icons = $socialiteEnum::getIcons();
                                    $drivers = $socialiteEnum::getAll();
                                    $activeDrivers = [];
                                    
                                    foreach ($drivers as $driver) {
                                        $driverName = $driver . '_login_status';
                                        if ($setting?->$driverName == 'active') {
                                            $activeDrivers[] = $driver;
                                        }
                                    }
                                @endphp
                                @if (count($activeDrivers) > 0)
                                    <p class="or">{{ __('OR') }}</p>
                                    <ul class="wsus__another_login d-flex flex-wrap">
                                        @foreach ($activeDrivers as $driver)
                                            <li>
                                                <a href="{{ route('auth.' . $driver) }}">
                                                    <span><img src="{{ asset($icons[$driver]) }}"
                                                            alt="{{ ucfirst($driver) }}" class="img-fluid"></span>
                                                    {{ ucfirst($driver) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                            <p class="dont_account mb-40">{{ __('Don’t have an account ?') }} <a
                                    href="{{ route('register') }}">{{ __('Sign Up for free') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
