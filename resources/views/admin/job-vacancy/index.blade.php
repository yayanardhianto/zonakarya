@extends('admin.master_layout')
@section('title', __('Job Vacancies'))

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Job Vacancies') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Job Vacancies') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('All Job Vacancies') }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.job-vacancy.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('Add New Job') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Position') }}</th>
                                                <th>{{ __('Location') }}</th>
                                                <th>{{ __('Work Type') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Views') }}</th>
                                                <th>{{ __('Created') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($jobs as $job)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.applicants.index', ['job_vacancy_id' => $job->id]) }}" class="text-decoration-none text-dark me-2"><strong>{{ $job->position }}</strong></a>
                                                        @if($job->application_deadline && $job->application_deadline < now())
                                                            <span class="badge badge-danger ml-2">{{ __('Expired') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $job->city }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $job->work_type }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $job->status === 'active' ? 'success' : ($job->status === 'inactive' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($job->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $job->views }}</td>
                                                    <td>{{ $job->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.job-vacancy.show', $job->unique_code) }}" 
                                                               class="btn btn-sm btn-info" 
                                                               data-toggle="tooltip" title="{{ __('View') }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.applicants.index', ['job_vacancy_id' => $job->id]) }}" 
                                                               class="btn btn-sm btn-success" 
                                                               data-toggle="tooltip" title="{{ __('View Applicants') }}">
                                                                <i class="fas fa-users"></i>
                                                            </a>
                                                            <a href="{{ route('admin.job-vacancy.edit', $job->unique_code) }}" 
                                                               class="btn btn-sm btn-primary" 
                                                               data-toggle="tooltip" title="{{ __('Edit') }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.job-vacancy.toggle-status', $job->unique_code) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-{{ $job->status === 'active' ? 'warning' : 'success' }}" 
                                                                        data-toggle="tooltip" 
                                                                        title="{{ $job->status === 'active' ? __('Deactivate') : __('Activate') }}">
                                                                    <i class="fas fa-{{ $job->status === 'active' ? 'pause' : 'play' }}"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.job-vacancy.destroy', $job->unique_code) }}" 
                                                                  method="POST" class="d-inline"
                                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this job vacancy?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger" 
                                                                        data-toggle="tooltip" title="{{ __('Delete') }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">{{ __('No job vacancies found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                    {{ $jobs->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#table-1').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "ordering": true,
            "columnDefs": [
                { "orderable": false, "targets": 7 }
            ]
        });
    });
</script>
@endpush
