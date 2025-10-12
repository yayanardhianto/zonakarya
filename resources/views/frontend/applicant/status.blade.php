@extends('frontend.layouts.master')
@section('title', 'Application Status - ' . $user->name . ' || ' . $setting->app_name)

@section('contents')
    <div class="application-status-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Header -->
                    <div class="status-header text-center mb-5">
                        <h1 class="mb-3">{{ __('Application Status') }}</h1>
                        <p class="text-muted">{{ __('Track your job application progress') }}</p>
                    </div>

                    <!-- User Info -->
                    <div class="applicant-info-card card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('User Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('Name') }}:</strong> {{ $user->name }}</p>
                                    <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('Total Applications') }}:</strong> {{ $applications->count() }}</p>
                                    <p><strong>{{ __('Last Applied') }}:</strong> {{ $applications->first() ? $applications->first()->created_at->format('d M Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications List -->
                    <div class="applications-list">
                        @forelse($applications as $application)
                            <div class="application-card card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $application->jobVacancy->position }}</h5>
                                    <span class="badge badge-{{ $application->status_badge }} badge-lg">
                                        {{ $application->status_text }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Company') }}:</strong> {{ $application->jobVacancy->company_name }}</p>
                                            <p><strong>{{ __('Location') }}:</strong> {{ $application->jobVacancy->location }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Work Type') }}:</strong> {{ $application->jobVacancy->work_type }}</p>
                                            <p><strong>{{ __('Salary') }}:</strong> {{ $application->jobVacancy->formatted_salary }}</p>
                                        </div>
                                    </div>

                                    <!-- Status Timeline -->
                                    <div class="status-timeline mt-4">
                                        <h6 class="mb-3">{{ __('Application Progress') }}</h6>
                                        <div class="timeline">
                                            <div class="timeline-item {{ $application->status == 'pending' ? 'active' : ($application->status == 'sent' || $application->status == 'check' || $application->status == 'short_call' ? 'completed' : '') }}">
                                                <div class="timeline-marker">
                                                    <i class="fas fa-paper-plane"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6>{{ __('Application Submitted') }}</h6>
                                                    <p class="text-muted">{{ $application->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                            </div>

                                            @if($application->status == 'sent' || $application->status == 'check' || $application->status == 'short_call')
                                                <div class="timeline-item {{ $application->status == 'sent' ? 'active' : ($application->status == 'check' || $application->status == 'short_call' ? 'completed' : '') }}">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Test Invitation Sent') }}</h6>
                                                        <p class="text-muted">{{ $application->test_sent_at ? $application->test_sent_at->format('d M Y H:i') : 'Pending' }}</p>
                                                        @if($application->testSession && $application->testSession->status === 'pending')
                                                            <a href="{{ route('test.take', ['session' => $application->testSession, 'token' => $application->testSession->access_token]) }}" 
                                                               class="btn btn-sm btn-primary">
                                                                <i class="fas fa-play"></i> {{ __('Take Test') }}
                                                            </a>
                                                        @elseif($application->testSession && $application->testSession->status === 'completed')
                                                            <p class="text-success">
                                                                <i class="fas fa-check"></i> {{ __('Test Completed') }} - {{ $application->test_completed_at ? $application->test_completed_at->format('d M Y H:i') : 'N/A' }}
                                                            </p>
                                                        @else
                                                            <p class="text-info">
                                                                <i class="fas fa-clock"></i> {{ __('Using previous screening test results') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'check' || $application->status == 'short_call')
                                                <div class="timeline-item {{ $application->status == 'check' ? 'active' : 'completed' }}">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Under Review') }}</h6>
                                                        <p class="text-muted">{{ __('HR is reviewing your application and test results') }}</p>
                                                        @if($application->test_completed_at)
                                                            <p class="text-success">
                                                                <i class="fas fa-check"></i> {{ __('Test Completed') }} - {{ $application->test_completed_at->format('d M Y H:i') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'short_call')
                                                <div class="timeline-item active">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Short Call Invitation') }}</h6>
                                                        <p class="text-muted">{{ __('You have been invited for a short call interview') }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'rejected')
                                                <div class="timeline-item rejected">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-times"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Application Rejected') }}</h6>
                                                        <p class="text-muted">{{ $application->notes ?? 'Thank you for your interest. Unfortunately, we have decided to move forward with other candidates.' }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Test Results -->
                                    @if($application->testSession && ($application->test_completed_at || $application->testSession->status === 'completed'))
                                        <div class="test-results mt-4">
                                            <h6 class="mb-3">{{ __('Test Results') }}</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-primary">{{ $application->test_score ?? ($application->testSession->score ?? 'N/A') }}</h4>
                                                        <p class="text-muted">{{ __('Score') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-success">{{ $application->test_completed_at ? $application->test_completed_at->format('d M Y') : ($application->testSession->completed_at ? $application->testSession->completed_at->format('d M Y') : 'N/A') }}</h4>
                                                        <p class="text-muted">{{ __('Completed') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-info">{{ $application->testSession->package->total_questions ?? 'N/A' }}</h4>
                                                        <p class="text-muted">{{ __('Questions') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="no-applications text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>{{ __('No Applications Found') }}</h5>
                                <p class="text-muted">{{ __('You haven\'t submitted any job applications yet.') }}</p>
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('Browse Jobs') }}
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons text-center mt-4">
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
@endsection

@push('css')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }
    
    .timeline-item.active {
        opacity: 1;
    }
    
    .timeline-item.completed {
        opacity: 1;
    }
    
    .timeline-item.rejected {
        opacity: 1;
    }
    
    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 2rem;
        height: 2rem;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.8rem;
    }
    
    .timeline-item.active .timeline-marker {
        background: #007bff;
        color: white;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #28a745;
        color: white;
    }
    
    .timeline-item.rejected .timeline-marker {
        background: #dc3545;
        color: white;
    }
    
    .timeline-content {
        padding-left: 1rem;
    }
    
    .result-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .application-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    
    .applicant-info-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>
@endpush
