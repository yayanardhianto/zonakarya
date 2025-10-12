@extends('admin.master_layout')
@section('title')
    <title>{{ __('Talent Details') }} - {{ $talent->name }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Talent Details') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Talents') => route('admin.talents.index'),
            $talent->name => '#',
        ]" />

        <div class="section-body">
            <div class="row">
                <!-- Talent Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Talent Information') }}</h4>
                        </div>
                        <div class="card-body text-center">
                            @if($talent->user && $talent->user->avatar)
                                <img src="{{ $talent->user->avatar }}" 
                                     alt="{{ $talent->name }}" 
                                     class="rounded-circle mb-3" 
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @elseif($talent->applicant && $talent->applicant->photo_path)
                                <img src="{{ asset('uploads/store/' . $talent->applicant->photo_path) }}" 
                                     alt="{{ $talent->name }}" 
                                     class="rounded-circle mb-3" 
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                     style="width: 120px; height: 120px; font-size: 3rem;">
                                    {{ substr($talent->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <h4>{{ $talent->name }}</h4>
                            @if($talent->user)
                                <p class="text-muted">{{ $talent->user->email }}</p>
                            @elseif($talent->applicant)
                                <p class="text-muted">{{ $talent->applicant->email }}</p>
                            @endif
                            
                            <div class="talent-info">
                                <p><i class="fas fa-map-marker-alt"></i> {{ $talent->city }}</p>
                                @if($talent->applicant)
                                    @if($talent->applicant->whatsapp && $talent->applicant->whatsapp != $talent->applicant->phone)
                                        <p><i class="fab fa-whatsapp"></i> {{ $talent->applicant->whatsapp }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Talent Details -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Talent Assessment') }}</h4>
                        </div>
                        <div class="card-body">
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Level Potential') }}</label>
                                        @if($talent->level_potential)
                                            <span class="badge badge-primary badge-lg">{{ $talent->level_potential }}</span>
                                        @else
                                            <span class="text-muted">{{ __('Not specified yet') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Talent Potential') }}</label>
                                        @if($talent->talent_potential)
                                            <span class="badge badge-info badge-lg">{{ $talent->talent_potential }}</span>
                                        @else
                                            <span class="text-muted">{{ __('Not specified yet') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Position Potential') }}</label>
                                @if($talent->potential_position)
                                    <span class="badge badge-secondary badge-lg">{{ $talent->potential_position }}</span>
                                @else
                                    <span class="text-muted">{{ __('Not specified yet') }}</span>
                                @endif
                            </div>

                            <hr>

                            <!-- Assessment Scores -->
                            <h5>{{ __('Assessment Scores') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Communication') }}</label>
                                        <div class="d-flex align-items-center">
                                            @if($talent->communication)
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $talent->communication ? 'text-warning' : 'text-muted' }} mr-1"></i>
                                                @endfor
                                                <span class="ml-2 badge badge-{{ $talent->communication >= 4 ? 'success' : ($talent->communication >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $talent->communication }}/5
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('Not assessed yet') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Attitude') }}</label>
                                        <div class="d-flex align-items-center">
                                            @if($talent->attitude_level)
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $talent->attitude_level ? 'text-warning' : 'text-muted' }} mr-1"></i>
                                                @endfor
                                                <span class="ml-2 badge badge-{{ $talent->attitude_level >= 4 ? 'success' : ($talent->attitude_level >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $talent->attitude_level }}/5
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('Not assessed yet') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Initiative') }}</label>
                                        <div class="d-flex align-items-center">
                                            @if($talent->initiative)
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $talent->initiative ? 'text-warning' : 'text-muted' }} mr-1"></i>
                                                @endfor
                                                <span class="ml-2 badge badge-{{ $talent->initiative >= 4 ? 'success' : ($talent->initiative >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $talent->initiative }}/5
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('Not assessed yet') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __('Leadership') }}</label>
                                        <div class="d-flex align-items-center">
                                            @if($talent->leadership)
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $talent->leadership ? 'text-warning' : 'text-muted' }} mr-1"></i>
                                                @endfor
                                                <span class="ml-2 badge badge-{{ $talent->leadership >= 4 ? 'success' : ($talent->leadership >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $talent->leadership }}/5
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('Not assessed yet') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($talent->notes)
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('Notes') }}</label>
                                    <div class="alert alert-info">
                                        {{ $talent->notes }}
                                    </div>
                                </div>
                            @endif

                            @if($talent->applicant)
                                <hr>
                                <h5>{{ __('Original Application Info') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Status') }}:</strong> 
                                            <span class="badge badge-{{ $talent->applicant->status_badge }}">
                                                {{ $talent->applicant->status_text }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Added to Talent Database') }}:</strong> 
                                            {{ $talent->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Application History -->
                    @if($applications && $applications->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Application History') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @foreach($applications as $application)
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">
                                                    {{ $application->jobVacancy->position ?? 'Unknown Position' }}
                                                    @if($application->status === 'rejected' && $application->last_stage)
                                                        <span class="badge badge-{{ $application->status_badge }} ml-2">
                                                            {{ $application->status_text }}
                                                        </span>
                                                        <span class="badge badge-info ml-1" title="Last stage before rejection">
                                                            {{ __('Last Stage') }}: {{ $application->last_stage_text }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-{{ $application->status_badge }} ml-2">
                                                            {{ $application->status_text }}
                                                        </span>
                                                    @endif
                                                </h6>
                                                <div class="timeline-body">
                                                    <p class="text-muted mb-1">
                                                        <strong>{{ __('Company') }}:</strong> {{ $application->jobVacancy->company_name ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-muted mb-1">
                                                        <strong>{{ __('Location') }}:</strong> {{ $application->jobVacancy->location ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-muted mb-1">
                                                        <strong>{{ __('Applied Date') }}:</strong> {{ $application->created_at->format('d M Y H:i') }}
                                                    </p>
                                                    @if($application->test_completed_at)
                                                        <p class="text-muted mb-1">
                                                            <strong>{{ __('Test Completed') }}:</strong> {{ $application->test_completed_at->format('d M Y H:i') }}
                                                        </p>
                                                    @endif
                                                    @if($application->test_score !== null)
                                                        <p class="text-muted mb-1">
                                                            <strong>{{ __('Test Score') }}:</strong> {{ $application->test_score }}%
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Actions') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.talents.edit', $talent) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> {{ __('Edit Talent') }}
                                </a>
                                @if($talent->applicant)
                                    <a href="{{ route('admin.applicants.show', $talent->applicant) }}" class="btn btn-info">
                                        <i class="fas fa-user"></i> {{ __('View Original Application') }}
                                    </a>
                                @endif
                                <a href="{{ route('admin.talents.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Talents') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')
<style>
.talent-info p {
    margin-bottom: 0.5rem;
}

.talent-info i {
    width: 20px;
    color: #6c757d;
}

.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 40px;
    height: 40px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.timeline-title {
    margin-bottom: 10px;
    color: #333;
}

.timeline-body p {
    margin-bottom: 5px;
}
</style>
@endpush
