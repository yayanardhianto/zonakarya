@extends('admin.auth.app')
@section('title')
    <title>{{ __('Login') }}</title>
@endsection
@section('content')
    <section class="section">
        <div class="container my-0">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-4 my-5">
                    <div class="login-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset($setting?->logo) }}" alt="{{ $setting?->app_name }}" width="220">
                        </a>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <x-admin.form-title :text="__('Admin Login')" />
                        </div>

                        <div class="card-body">
                            <form novalidate="" id="adminLoginForm" action="{{ route('admin.store-login') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                        <x-admin.form-input type="email" id="email" name="email"
                                            label="{{ __('Email') }}" value="admin@gmail.com" required="true" />
                                    @else
                                        <x-admin.form-input type="email" id="email" name="email"
                                            label="{{ __('Email') }}" value="{{ old('email') }}" required="true" />
                                    @endif
                                </div>

                                <div class="form-group">
                                    @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                        <x-admin.form-input type="password" id="password" label="{{ __('Password') }}"
                                            name="password" value="1234" required="true" />
                                    @else
                                        <x-admin.form-input type="password" id="password" label="{{ __('Password') }}"
                                            name="password" required="true" />
                                    @endif
                                </div>

                                <div class="form-group d-flex justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" tabindex="3"
                                            id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>
                                    <a href="{{ route('admin.password.request') }}" class="text-small">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                </div>

                                <div class="form-group">
                                    <x-admin.button type="submit" class="btn-lg btn-block" text="{{ __('Login') }}" />
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
