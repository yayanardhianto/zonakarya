@extends('admin.auth.app')
@section('title')
    <title>{{ __('Reset Password') }}</title>
@endsection
@section('content')
    <section class="section">
        <div class="container mt-0">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-4 my-5">
                    <div class="login-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset($setting?->logo) }}" alt="{{ $setting?->app_name }}" width="220">
                        </a>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <x-admin.form-title :text="__('Reset Password')" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.password.reset-store', $token) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <x-admin.form-input type="email" id="email"  name="email" label="{{ __('Email') }}" value="{{ $admin->email }}" required="true"/>
                                </div>
                                <div class="form-group">
                                    <x-admin.form-input type="password" id="password" label="{{ __('Password') }}" name="password" required="true"/>
                                </div>
                                <div class="form-group">
                                    <x-admin.form-input type="password" id="password_confirmation" label="{{ __('Confirm Password') }}" name="password_confirmation" required="true"/>
                                </div>

                                <div class="form-group">
                                    <x-admin.button type="submit" class="btn-lg btn-block" text="{{ __('Reset Password') }}" />
                                </div>
                                <div class="form-group">
                                    <div class="d-block">
                                        <a href="{{ route('admin.login') }}">{{ __('Go to login page') }} -> </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

