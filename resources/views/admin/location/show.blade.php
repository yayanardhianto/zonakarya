@extends('admin.master_layout')
@section('title', __('Location Details'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Location Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Location Details') }}</div>
            </div>
        </div>
        <div class="section-body">          <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">{{ __('Location Details') }}</h3>
                            <div>
                                <a href="{{ route('admin.location.edit', $location) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> {{ __('Edit') }}
                                </a>
                                <a href="{{ route('admin.location.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">{{ __('Name') }}:</th>
                                        <td>{{ $location->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('City') }}:</th>
                                        <td>{{ $location->city }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Province') }}:</th>
                                        <td>{{ $location->province }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Postal Code') }}:</th>
                                        <td>{{ $location->postal_code ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Status') }}:</th>
                                        <td>
                                            <span class="badge badge-{{ $location->is_active ? 'success' : 'danger' }}">
                                                {{ $location->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">{{ __('Phone') }}:</th>
                                        <td>{{ $location->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Email') }}:</th>
                                        <td>{{ $location->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Created') }}:</th>
                                        <td>{{ $location->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Updated') }}:</th>
                                        <td>{{ $location->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($location->address)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>{{ __('Address') }}</h5>
                                    <p class="text-muted">{{ $location->address }}</p>
                                </div>
                            </div>
                        @endif

                        @if($location->description)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>{{ __('Description') }}</h5>
                                    <p class="text-muted">{{ $location->description }}</p>
                                </div>
                            </div>
                        @endif

                        @if($location->jobVacancies->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5>{{ __('Job Vacancies at this Location') }}</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Position') }}</th>
                                                    <th>{{ __('Work Type') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Created') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($location->jobVacancies as $job)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('admin.job-vacancy.show', $job) }}">
                                                                {{ $job->position }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $job->work_type }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $job->status === 'active' ? 'success' : ($job->status === 'inactive' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($job->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $job->created_at->format('d M Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
