@extends('frontend.layouts.master')

@section('title', __('My Applications'))

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">{{ __('My Applications') }}</h2>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Apply for More Jobs') }}
                </a>
            </div>

            @if($applications->count() > 0)
                <div class="row">
                    @foreach($applications as $application)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title text-primary">{{ $application->jobVacancy->position }}</h5>
                                        <span class="badge bg-{{ $application->getStatusBadgeAttribute() }}">
                                            {{ $application->getStatusTextAttribute() }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-building"></i> {{ $application->jobVacancy->company_name }}
                                    </p>
                                    
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-map-marker-alt"></i> {{ $application->jobVacancy->location }}
                                    </p>

                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> 
                                            {{ __('Applied on') }}: {{ $application->created_at->format('M d, Y') }}
                                        </small>
                                    </div>

                                    @if($application->testSession)
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <span class="text-muted">{{ __('Test Status') }}:</span>
                                                <span class="badge bg-{{ $application->testSession->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($application->testSession->status) }}
                                                </span>
                                            </div>
                                            @if($application->testSession->expires_at)
                                                <small class="text-muted">
                                                    {{ __('Expires') }}: {{ $application->testSession->expires_at->format('M d, Y H:i') }}
                                                </small>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user.applications.show', $application) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye"></i> {{ __('View Details') }}
                                        </a>
                                        
                                        @if($application->testSession && $application->testSession->status === 'pending')
                                            <a href="{{ route('test.take', $application->testSession) }}?token={{ $application->testSession->access_token }}" class="btn btn-success btn-sm flex-fill">
                                                <i class="fas fa-play"></i> {{ __('Start Test') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-alt text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted">{{ __('No Applications Yet') }}</h4>
                    <p class="text-muted">{{ __('You haven\'t applied to any jobs yet. Start your job search today!') }}</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> {{ __('Browse Jobs') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
        border: none;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .text-primary {
        color: #007bff !important;
    }
</style>
@endpush
