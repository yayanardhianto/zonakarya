@extends('frontend.layouts.master')

@section('title', __('Application Details'))

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">{{ __('Application Details') }}</h2>
                <a href="{{ route('user.applications') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Applications') }}
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Job Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Job Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <h4 class="text-primary mb-3">{{ $application->jobVacancy->position }}</h4>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>{{ __('Company') }}:</strong> {{ $application->jobVacancy->company_name }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>{{ __('Location') }}:</strong> {{ $application->jobVacancy->location }}
                                    </p>
                                </div>
                            </div>

                            @if($application->jobVacancy->description)
                                <div class="mb-3">
                                    <h6>{{ __('Job Description') }}</h6>
                                    <p class="text-muted">{{ $application->jobVacancy->description }}</p>
                                </div>
                            @endif

                            @if($application->jobVacancy->specific_requirements)
                                <div class="mb-3">
                                    <h6>{{ __('Requirements') }}</h6>
                                    @if(is_array($application->jobVacancy->specific_requirements))
                                        <ul class="text-muted">
                                            @foreach($application->jobVacancy->specific_requirements as $requirement)
                                                <li>{{ $requirement }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">{{ $application->jobVacancy->specific_requirements }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($application->jobVacancy->benefits)
                                <div class="mb-3">
                                    <h6>{{ __('Benefits') }}</h6>
                                    <p class="text-muted">{{ $application->jobVacancy->benefits }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Application Status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>{{ __('Status') }}:</strong>
                                        <span class="badge bg-{{ $application->getStatusBadgeAttribute() }} ms-2">
                                            {{ $application->getStatusTextAttribute() }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>{{ __('Applied Date') }}:</strong>
                                        {{ $application->created_at->format('M d, Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            @if($application->test_sent_at)
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>{{ __('Test Sent') }}:</strong>
                                            {{ $application->test_sent_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Test Information -->
                    @if($application->testSession)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Test Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <strong>{{ __('Test Status') }}:</strong>
                                        <span class="badge bg-{{ $application->testSession->status === 'completed' ? 'success' : 'warning' }} ms-2">
                                            {{ ucfirst($application->testSession->status) }}
                                        </span>
                                    </p>
                                </div>

                                @if($application->testSession->expires_at)
                                    <div class="mb-3">
                                        <p class="mb-2">
                                            <strong>{{ __('Expires') }}:</strong>
                                            {{ $application->testSession->expires_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                @endif

                                @if($application->testSession->status === 'pending')
                                    <div class="d-grid">
                                        <a href="{{ route('test.take', $application->testSession) }}?token={{ $application->testSession->access_token }}" class="btn btn-success">
                                            <i class="fas fa-play"></i> {{ __('Start Test') }}
                                        </a>
                                    </div>
                                @elseif($application->testSession->status === 'completed')
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i>
                                        {{ __('Test completed successfully!') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Applicant Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Your Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>{{ __('Name') }}:</strong> {{ $application->applicant->name }}
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Email') }}:</strong> {{ $application->applicant->email }}
                            </p>
                            @if($application->applicant->whatsapp)
                                <p class="mb-2">
                                    <strong>{{ __('WhatsApp') }}:</strong> {{ $application->applicant->whatsapp }}
                                </p>
                            @endif
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
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .text-primary {
        color: #007bff !important;
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush
