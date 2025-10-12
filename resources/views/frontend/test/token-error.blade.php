@extends('frontend.layouts.master')
@section('title', __('Token Error'))

@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-center bg-danger text-white">
                    <h3 class="mb-0">{{ __('Test Session Error') }}</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                        <h4>{{ __('Invalid or Expired Token') }}</h4>
                        <p class="text-muted">{{ __('Your test session token is invalid or has expired.') }}</p>
                    </div>
                    
                    <div class="alert alert-info text-start">
                        <h6><i class="fas fa-info-circle"></i> {{ __('Possible Causes:') }}</h6>
                        <ul class="mb-0">
                            <li>{{ __('Token has expired (valid until end of day)') }}</li>
                            <li>{{ __('Session was deleted or corrupted') }}</li>
                            <li>{{ __('Invalid token in URL') }}</li>
                            <li>{{ __('Session belongs to different user') }}</li>
                        </ul>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('test.regenerate-token', $sessionId) }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-refresh"></i> {{ __('Regenerate Token') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('test.index') }}" class="btn btn-secondary btn-lg w-100 mb-3">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Tests') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            {{ __('If the problem persists, please contact support.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
