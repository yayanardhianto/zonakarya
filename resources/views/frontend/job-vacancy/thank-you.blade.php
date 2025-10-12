@extends('frontend.layouts.master')
@section('title', 'Thank You - Application Submitted || ' . $setting->app_name)
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <div class="thank-you-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="thank-you-card card text-center">
                        <div class="card-body py-5">
                            <div class="success-icon mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            
                            <h1 class="card-title mb-4">{{ __('Thank You for Your Application!') }}</h1>
                            
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Your application has been successfully submitted. We will contact you soon for the next steps.') }}
                            </div>
                            
                            <div class="whatsapp-info mb-4">
                                <h5 class="mb-3">{{ __('Next Steps') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="step-item mb-3">
                                            <i class="fas fa-mobile-alt text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6>{{ __('WhatsApp Notification') }}</h6>
                                            <p class="text-muted">{{ __('You will receive a WhatsApp message with test screening details.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="step-item mb-3">
                                            <i class="fas fa-clock text-warning mb-2" style="font-size: 2rem;"></i>
                                            <h6>{{ __('Test Link Validity') }}</h6>
                                            <p class="text-muted">{{ __('The test link is valid only for today.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="application-details mb-4">
                                <h5 class="mb-3">{{ __('Application Details') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Name') }}:</strong> {{ $applicant->name }}</p>
                                        <p><strong>{{ __('Email') }}:</strong> {{ $applicant->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>{{ __('WhatsApp') }}:</strong> {{ $applicant->whatsapp }}</p>
                                        <p><strong>{{ __('Status') }}:</strong> 
                                            <span class="badge badge-{{ $applicant->status_badge }}">{{ $applicant->status_text }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                @auth
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-user-check"></i>
                                    <strong>{{ __('You are now logged in!') }}</strong>
                                    <p class="mb-0">{{ __('You can now apply to other jobs without needing to register again.') }}</p>
                                </div>
                                @endauth
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('applicant.status') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line"></i> {{ __('Track Application Status') }}
                                </a>
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('Browse More Jobs') }}
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> {{ __('Back to Home') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .thank-you-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
    }
    
    .success-icon {
        animation: bounce 1s ease-in-out;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    
    .step-item {
        padding: 1rem;
        border-radius: 10px;
        background: #f8f9fa;
        transition: transform 0.3s ease;
    }
    
    .step-item:hover {
        transform: translateY(-5px);
    }
    
    .application-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-success {
        background-color: #28a745;
        color: white;
    }
</style>
@endpush
