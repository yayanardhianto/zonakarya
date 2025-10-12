@extends('admin.master_layout')
@section('title', __('Test Sessions'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Sessions') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Sessions') }}</div>
            </div>
        </div>
        <div class="section-body">        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Test Sessions') }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Filter Form -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <form method="GET" class="row g-3">
                                        <div class="col-md-3">
                                            <select name="status" class="form-select">
                                                <option value="">{{ __('All Status') }}</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                                    {{ __('In Progress') }}
                                                </option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                                    {{ __('Completed') }}
                                                </option>
                                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                                    {{ __('Expired') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="package_id" class="form-select">
                                                <option value="">{{ __('All Packages') }}</option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_from" class="form-control" 
                                                value="{{ request('date_from') }}" placeholder="{{ __('From Date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_to" class="form-control" 
                                                value="{{ request('date_to') }}" placeholder="{{ __('To Date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-outline-primary w-100">{{ __('Filter') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Applicant/User') }}</th>
                                            <th>{{ __('Package') }}</th>
                                            <th>{{ __('Job') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Score') }}</th>
                                            <th>{{ __('Progress') }}</th>
                                            <th>{{ __('Started At') }}</th>
                                            <th>{{ __('Completed At') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sessions as $session)
                                            <tr>
                                                <td>{{ $session->id }}</td>
                                                <td>
                                                    @if($session->applicant)
                                                        {{ $session->applicant->name }}
                                                        <br><small class="text-muted">{{ $session->applicant->email }}</small>
                                                    @elseif($session->user  )
                                                        {{ $session->user->name }}
                                                        <br><small class="text-muted">{{ $session->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">{{ __('N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $session->package->name }}</span>
                                                    <br><small class="text-muted">{{ $session->package->category->name }}</small>
                                                </td>
                                                <td>
                                                    @if($session->jobVacancy)
                                                        {{ $session->jobVacancy->position }}
                                                    @else
                                                        <span class="text-muted">{{ __('N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($session->status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                            @break
                                                        @case('in_progress')
                                                            <span class="badge bg-primary">{{ __('In Progress') }}</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge bg-success">{{ __('Completed') }}</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="badge bg-danger">{{ __('Expired') }}</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($session->score !== null)
                                                        <span class="badge {{ $session->is_passed ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $session->score }}%
                                                        </span>
                                                        @if($session->is_passed)
                                                            <br><small class="text-success">{{ __('Passed') }}</small>
                                                        @else
                                                            <br><small class="text-danger">{{ __('Failed') }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">{{ __('N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->isInProgress())
                                                        <div class="progress" style="width: 100px;">
                                                            <div class="progress-bar" role="progressbar" 
                                                                style="width: {{ $session->progress_percentage }}%">
                                                                {{ $session->progress_percentage }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">{{ __('N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->started_at)
                                                        {{ $session->started_at->format('d M Y H:i') }}
                                                    @else
                                                        <span class="text-muted">{{ __('Not Started') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->completed_at)
                                                        {{ $session->completed_at->format('d M Y H:i') }}
                                                    @else
                                                        <span class="text-muted">{{ __('Not Completed') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(checkAdminHasPermission('test.session.view'))
                                                            <a href="{{ route('admin.test-session.show', $session) }}" 
                                                            class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.session.delete'))
                                                            <form action="{{ route('admin.test-session.destroy', $session) }}" 
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this session?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">{{ __('No sessions found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $sessions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
