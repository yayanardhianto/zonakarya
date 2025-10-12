@extends('admin.auth.app')
@section('title')
    <title>{{ __('Forgot Password') }}</title>
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
                            <x-admin.form-title :text="__('Forgot Password')" />
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.forget-password') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input type="email" id="email"  name="email" label="{{ __('Email') }}" value="{{ old('email') }}" required="true"/>
                                </div>
                                <div class="form-group">
                                    <x-admin.button type="submit" class="btn-lg btn-block" text="{{ __('Send Reset Link') }}" />
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
