@extends('frontend.layouts.master')

@section('title')
    {{ __('Verify Your Email Address') }}
@endsection

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">{{ __('Verify Your Email Address') }}</h4>
                    </div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <p class="mb-3">
                                {{ __('Before proceeding, please check your email for a verification link.') }}
                            </p>
                            <p class="mb-3">
                                {{ __('If you did not receive the email') }},
                            </p>
                        </div>

                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ __('Click here to request another') }}
                            </button>
                        </form>

                        <div class="mt-4">
                            <p class="text-muted">
                                {{ __('Email address') }}: <strong>{{ auth()->user()->email }}</strong>
                            </p>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary">
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
    padding: 20px 0;
}

.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 20px;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 30px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.alert-info {
    background-color: #e3f2fd;
    border-color: #bbdefb;
    color: #1565c0;
}

.alert-success {
    background-color: #e8f5e8;
    border-color: #c8e6c9;
    color: #2e7d32;
}
</style>
@endsection
