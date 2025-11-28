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
                                <div class="d-flex gap-2">
                                    <!-- Export Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> {{ __('Export') }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                            <li><a class="dropdown-item" href="#" onclick="exportData('excel')"><i class="fas fa-file-excel text-success me-1"></i> {{ __('Export to Excel') }}</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')"><i class="fas fa-file-pdf text-danger me-1"></i> {{ __('Export to PDF') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Filter Form -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <form method="GET" class="row g-3">
                                        <div class="col-md-2">
                                            <label for="status" class="form-label">{{ __('Status') }}</label>
                                            <select name="status" id="status" class="form-select">
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
                                        <div class="col-md-2">
                                            <label for="category_id" class="form-label">{{ __('Category') }}</label>
                                            <select name="category_id" id="category_id" class="form-select" onchange="updatePackageOptions()">
                                                <option value="">{{ __('All Categories') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" data-packages="{{ $category->testPackages->pluck('id')->join(',') }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="package_id" class="form-label">{{ __('Package') }}</label>
                                            <select name="package_id" id="package_id" class="form-select">
                                                <option value="">{{ __('All Packages') }}</option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" data-category="{{ $package->category_id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="score_filter" class="form-label">{{ __('Score') }}</label>
                                            <select name="score_filter" id="score_filter" class="form-select" onchange="toggleScoreInput()">
                                                <option value="">{{ __('All Scores') }}</option>
                                                <option value="passed" {{ request('score_filter') == 'passed' ? 'selected' : '' }}>
                                                    {{ __('Passed') }}
                                                </option>
                                                <option value="failed" {{ request('score_filter') == 'failed' ? 'selected' : '' }}>
                                                    {{ __('Failed') }}
                                                </option>
                                                <option value="greater_than" {{ request('score_filter') == 'greater_than' ? 'selected' : '' }}>
                                                    {{ __('Greater Than') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2" id="score_value_container" style="display: none;">
                                            <label for="score_value" class="form-label">{{ __('Score Value') }}</label>
                                            <input type="number" name="score_value" id="score_value" class="form-control" 
                                                min="0" max="100" step="1" value="{{ request('score_value') }}" 
                                                placeholder="{{ __('e.g., 70') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_from" class="form-label">{{ __('From Date') }}</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control" 
                                                value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_to" class="form-label">{{ __('To Date') }}</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control" 
                                                value="{{ request('date_to') }}">
                                        </div>
                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <div class="col-md-1">
                                                <a href="{{ route('admin.test-session.index') }}" class="btn btn-outline-secondary w-100">
                                                    <i class="fas fa-times me-1"></i>{{ __('Clear') }}
                                                </a>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-search me-1"></i>{{ __('Filter') }}
                                                </button>
                                            </div>

                                        </div>      
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(request()->hasAny(['status', 'package_id', 'category_id', 'score_filter', 'date_from', 'date_to']))
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('Filtered Results') }}:</strong>
                                    @if(request('category_id'))
                                        {{ __('Category') }}: {{ \App\Models\TestCategory::find(request('category_id'))->name ?? 'N/A' }}
                                    @endif
                                    @if(request('status'))
                                        | {{ __('Status') }}: {{ ucfirst(request('status')) }}
                                    @endif
                                    @if(request('package_id'))
                                        | {{ __('Package') }}: {{ \App\Models\TestPackage::find(request('package_id'))->name ?? 'N/A' }}
                                    @endif
                                    @if(request('score_filter'))
                                        | {{ __('Score') }}: 
                                        @if(request('score_filter') === 'passed')
                                            {{ __('Passed') }}
                                        @elseif(request('score_filter') === 'failed')
                                            {{ __('Failed') }}
                                        @elseif(request('score_filter') === 'greater_than')
                                            {{ __('Greater Than') }} {{ request('score_value') }}%
                                        @else
                                            {{ ucfirst(request('score_filter')) }}
                                        @endif
                                    @endif
                                    @if(request('date_from'))
                                        | {{ __('From Date') }}: {{ request('date_from') }}
                                    @endif
                                    @if(request('date_to'))
                                        | {{ __('To Date') }}: {{ request('date_to') }}
                                    @endif
                                    <br>
                                    <small>{{ __('Showing') }} {{ $sessions->total() }} {{ __('results') }}. {{ __('Export will include only the filtered results shown below.') }}</small>
                                </div>
                            @else
                                <div class="alert alert-light">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('All Results') }}:</strong>
                                    <small>{{ __('Showing') }} {{ $sessions->total() }} {{ __('total sessions') }}.</small>
                                </div>
                            @endif



                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="min-width:90px;">
                                                <a href="{{ route('admin.test-session.index', array_merge(request()->query(), ['sort_by' => 'id', 'sort_order' => $sortBy === 'id' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                                    {{ __('ID') }}
                                                    @if($sortBy === 'id')
                                                        <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>{{ __('Applicant/User') }}</th>
                                            <th>{{ __('Package') }}</th>
                                            <th>
                                                <a href="{{ route('admin.test-session.index', array_merge(request()->query(), ['sort_by' => 'status', 'sort_order' => $sortBy === 'status' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                                    {{ __('Status') }}
                                                    @if($sortBy === 'status')
                                                        <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>{{ __('Job') }}</th>
                                            <th style="min-width:120px;">
                                                <a href="{{ route('admin.test-session.index', array_merge(request()->query(), ['sort_by' => 'score', 'sort_order' => $sortBy === 'score' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                                    {{ __('Score') }}
                                                    @if($sortBy === 'score')
                                                        <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>{{ __('Progress') }}</th>
                                            <th>
                                                <a href="{{ route('admin.test-session.index', array_merge(request()->query(), ['sort_by' => 'started_at', 'sort_order' => $sortBy === 'started_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                                    {{ __('Started At') }}
                                                    @if($sortBy === 'started_at')
                                                        <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <a href="{{ route('admin.test-session.index', array_merge(request()->query(), ['sort_by' => 'completed_at', 'sort_order' => $sortBy === 'completed_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                                    {{ __('Completed At') }}
                                                    @if($sortBy === 'completed_at')
                                                        <i class="fas fa-sort-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
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
                                                <td class="py-3">
                                                    <span class="badge bg-secondary">{{ $session->package->name }}</span>
                                                    <br><small class="text-muted">{{ $session->package->category->name }}</small>
                                                </td>
                                                <td>
                                                    @if($session->jobVacancy)
                                                        {{ $session->jobVacancy->position }}
                                                    @elseif($session->application && $session->application->jobVacancy)
                                                        {{ $session->application->jobVacancy->position }}
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
                                                <td class="py-2 px-1 text-center">
                                                    @if($session->score !== null)
                                                        <span class="badge {{ $session->is_passed ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $session->score }}%
                                                        </span>
                                                        @if($session->is_passed)
                                                            <br><small class="text-success">{{ __('Passed') }}</small>
                                                        @else
                                                            <br><small class="text-danger">{{ __('Failed') }}</small>
                                                        @endif
                                                    @elseif(isset($session->multiple_choice_score) && $session->multiple_choice_score !== null)
                                                        <span class="badge {{ $session->multiple_choice_is_passed ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $session->multiple_choice_score }}%
                                                        </span>
                                                        @if($session->multiple_choice_is_passed)
                                                            <br><small class="text-success">{{ __('Passed') }}</small>
                                                        @else
                                                            <br><small class="text-danger">{{ __('Failed') }}</small>
                                                        @endif
                                                        <br><small class="text-muted text-small fs-xs"><i class="fas fa-info-circle text-small ms-1"></i> {{ __('MC Only') }}</small>
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
                                {{ $sessions->appends(request()->query())->links() }}
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
    table thead th a {
        color: inherit;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    table thead th a:hover {
        color: #007bff;
    }
    
    table thead th i {
        margin-left: 0.3rem;
        opacity: 0.6;
    }
    
    table thead th a:hover i {
        opacity: 1;
    }
</style>
@endpush

@push('js')
<script>
// Export functionality
function exportData(type) {
    // Get current filter parameters
    const categoryId = document.querySelector('select[name="category_id"]').value;
    const status = document.querySelector('select[name="status"]').value;
    const packageId = document.querySelector('select[name="package_id"]').value;
    const scoreFilter = document.querySelector('select[name="score_filter"]').value;
    const scoreValue = document.querySelector('input[name="score_value"]').value;
    const dateFrom = document.querySelector('input[name="date_from"]').value;
    const dateTo = document.querySelector('input[name="date_to"]').value;
    
    // Build export URL with current filters
    let exportUrl = '';
    if (type === 'excel') {
        exportUrl = '{{ route("admin.test-session.export-excel") }}';
    } else if (type === 'pdf') {
        exportUrl = '{{ route("admin.test-session.export-pdf") }}';
    }
    
    // Add filter parameters to URL
    const params = new URLSearchParams();
    if (categoryId) params.append('category_id', categoryId);
    if (status) params.append('status', status);
    if (packageId) params.append('package_id', packageId);
    if (scoreFilter) params.append('score_filter', scoreFilter);
    if (scoreValue) params.append('score_value', scoreValue);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Open export URL in new window
    window.open(exportUrl, '_blank');
}

// Toggle score input field based on score filter selection
function toggleScoreInput() {
    const scoreFilter = document.getElementById('score_filter').value;
    const scoreValueContainer = document.getElementById('score_value_container');
    
    if (scoreFilter === 'greater_than') {
        scoreValueContainer.style.display = 'block';
    } else {
        scoreValueContainer.style.display = 'none';
    }
}

// Update package options based on category selection
function updatePackageOptions() {
    const categorySelect = document.getElementById('category_id');
    const packageSelect = document.getElementById('package_id');
    const selectedCategoryId = categorySelect.value;
    
    // Get all package options
    const allOptions = packageSelect.querySelectorAll('option');
    
    // Show/hide options based on category
    allOptions.forEach(option => {
        if (option.value === '') {
            // Always show "All Packages" option
            option.style.display = 'block';
        } else {
            const packageCategoryId = option.getAttribute('data-category');
            if (selectedCategoryId === '') {
                // Show all when no category selected
                option.style.display = 'block';
            } else if (packageCategoryId === selectedCategoryId) {
                // Show only matching category
                option.style.display = 'block';
            } else {
                // Hide non-matching
                option.style.display = 'none';
            }
        }
    });
    
    // Reset package selection if it's hidden
    if (packageSelect.selectedOptions[0].style.display === 'none') {
        packageSelect.value = '';
    }
}

// Initialize package options on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePackageOptions();
    toggleScoreInput();
    
    const filterForm = document.querySelector('form[method="GET"]');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');
    
    
    // Optional: Auto-submit when filter changes (uncomment if desired)
    // filterInputs.forEach(input => {
    //     input.addEventListener('change', function() {
    //         filterForm.submit();
    //     });
    // });
});
</script>
@endpush

