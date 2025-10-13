@extends('admin.master_layout')
@section('title', __('View Job Vacancy'))

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Job Vacancy Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.job-vacancy.index') }}">{{ __('Job Vacancies') }}</a></div>
                    <div class="breadcrumb-item">{{ __('View') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $jobVacancy->position }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.job-vacancy.edit', $jobVacancy->unique_code) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                                    </a>
                                    <a href="{{ route('admin.job-vacancy.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="job-details">
                                            <h5 class="text-primary mb-3">{{ __('Job Information') }}</h5>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>{{ __('Position') }}:</strong>
                                                    <p>{{ $jobVacancy->position }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Location') }}:</strong>
                                                    <p>{{ $jobVacancy->location }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <strong>{{ __('Work Type') }}:</strong>
                                                    <p><span class="badge badge-info">{{ $jobVacancy->work_type }}</span></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>{{ __('Education') }}:</strong>
                                                    <p>{{ $jobVacancy->education }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>{{ __('Gender') }}:</strong>
                                                    <p>{{ $jobVacancy->gender }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>{{ __('Salary Range') }}:</strong>
                                                    <p>{{ $jobVacancy->formatted_salary }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Age Range') }}:</strong>
                                                    <p>{{ $jobVacancy->formatted_age }}</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>{{ __('Experience Required') }}:</strong>
                                                    <p>{{ $jobVacancy->experience_years }} {{ __('years') }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Application Deadline') }}:</strong>
                                                    <p>{{ $jobVacancy->application_deadline ? $jobVacancy->application_deadline->format('d M Y') : __('No deadline') }}</p>
                                                </div>
                                            </div>

                                            @if($jobVacancy->specific_requirements && count($jobVacancy->specific_requirements) > 0)
                                                <div class="mb-3">
                                                    <strong>{{ __('Specific Requirements') }}:</strong>
                                                    <ul class="list-unstyled mt-2">
                                                        @foreach($jobVacancy->specific_requirements as $requirement)
                                                            <li><i class="fas fa-check text-success mr-2"></i>{{ $requirement }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <strong>{{ __('Description') }}:</strong>
                                                <div class="mt-2">
                                                    {!! nl2br(e($jobVacancy->description)) !!}
                                                </div>
                                            </div>

                                            @if($jobVacancy->responsibilities)
                                                <div class="mb-3">
                                                    <strong>{{ __('Responsibilities') }}:</strong>
                                                    <div class="mt-2">
                                                        {!! nl2br(e($jobVacancy->responsibilities)) !!}
                                                    </div>
                                                </div>
                                            @endif

                                            @if($jobVacancy->benefits)
                                                <div class="mb-3">
                                                    <strong>{{ __('Benefits') }}:</strong>
                                                    <div class="mt-2">
                                                        {!! nl2br(e($jobVacancy->benefits)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="company-info">
                                            <h5 class="text-primary mb-3">{{ __('Company Information') }}</h5>
                                            
                                            <div class="text-center mb-3">
                                                @if($jobVacancy->company_logo)
                                                    <img src="{{ asset('storage/' . $jobVacancy->company_logo) }}" 
                                                         alt="{{ $jobVacancy->company_name }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-height: 100px;">
                                                @else
                                                    <div class="bg-light rounded p-4">
                                                        <i class="fas fa-building fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <strong>{{ __('Company Name') }}:</strong>
                                                <p>{{ $jobVacancy->company_name }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <strong>{{ __('Contact Email') }}:</strong>
                                                <p><a href="mailto:{{ $jobVacancy->contact_email }}">{{ $jobVacancy->contact_email }}</a></p>
                                            </div>

                                            @if($jobVacancy->contact_phone)
                                                <div class="mb-3">
                                                    <strong>{{ __('Contact Phone') }}:</strong>
                                                    <p><a href="tel:{{ $jobVacancy->contact_phone }}">{{ $jobVacancy->contact_phone }}</a></p>
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <strong>{{ __('Status') }}:</strong>
                                                <p>
                                                    <span class="badge badge-{{ $jobVacancy->status === 'active' ? 'success' : ($jobVacancy->status === 'inactive' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($jobVacancy->status) }}
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="mb-3">
                                                <strong>{{ __('Views') }}:</strong>
                                                <p>{{ $jobVacancy->views }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <strong>{{ __('Created') }}:</strong>
                                                <p>{{ $jobVacancy->created_at->format('d M Y H:i') }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <strong>{{ __('Last Updated') }}:</strong>
                                                <p>{{ $jobVacancy->updated_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
