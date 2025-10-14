@extends('admin.master_layout')
@section('title', __('Test Packages'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Packages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Packages') }}</div>
            </div>
        </div>
        <div class="section-body">        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Test Packages') }}</h3>
                                <div class="d-flex gap-2">
                                    <!-- Export Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> {{ __('Export') }}
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                            <li><a class="dropdown-item" href="#" onclick="exportData('excel')"><i class="fas fa-file-excel text-success  me-1"></i> {{ __('Export to Excel') }}</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')"><i class="fas fa-file-pdf text-danger me-1"></i> {{ __('Export to PDF') }}</a></li>
                                        </ul>
                                    </div>
                                    @if(checkAdminHasPermission('test.package.create'))
                                        <a href="{{ route('admin.test-package.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('Add New Package') }}
                                        </a>
                                    @endif
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
                                            <form method="GET" action="{{ route('admin.test-package.index') }}" id="filterForm">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="category_id" class="form-label">{{ __('Category') }}</label>
                                                        <select name="category_id" id="category_id" class="form-select">
                                                            <option value="">{{ __('All Categories') }}</option>
                                                            @foreach(\App\Models\TestCategory::active()->get() as $category)
                                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="status" class="form-label">{{ __('Status') }}</label>
                                                        <select name="status" id="status" class="form-select">
                                                            <option value="">{{ __('All Status') }}</option>
                                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="applicant_flow" class="form-label">{{ __('Type') }}</label>
                                                        <select name="applicant_flow" id="applicant_flow" class="form-select">
                                                            <option value="">{{ __('All Types') }}</option>
                                                            <option value="1" {{ request('applicant_flow') === '1' ? 'selected' : '' }}>{{ __('Applicant Flow') }}</option>
                                                            <option value="0" {{ request('applicant_flow') === '0' ? 'selected' : '' }}>{{ __('General Test') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">&nbsp;</label>
                                                        <div class="d-flex gap-2">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-search"></i> {{ __('Filter') }}
                                                            </button>
                                                            <a href="{{ route('admin.test-package.index') }}" class="btn btn-outline-secondary">
                                                                <i class="fas fa-times"></i> {{ __('Clear') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(request()->hasAny(['category_id', 'status', 'applicant_flow']))
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('Filtered Results') }}:</strong>
                                    @if(request('category_id'))
                                        {{ __('Category') }}: {{ \App\Models\TestCategory::find(request('category_id'))->name ?? 'N/A' }}
                                    @endif
                                    @if(request('status') !== null && request('status') !== '')
                                        | {{ __('Status') }}: {{ request('status') ? __('Active') : __('Inactive') }}
                                    @endif
                                    @if(request('applicant_flow') !== null && request('applicant_flow') !== '')
                                        | {{ __('Type') }}: {{ request('applicant_flow') ? __('Applicant Flow') : __('General Test') }}
                                    @endif
                                    <br>
                                    <small>{{ __('Export will include only the filtered results shown below.') }}</small>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Package Name') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Questions') }}</th>
                                            <th>{{ __('Passing Score') }}</th>
                                            <th>{{ __('Sessions') }}</th>
                                            <th>{{ __('Applicant Flow') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packages as $package)
                                            <tr>
                                                <td>{{ $package->id }}</td>
                                                <td>{{ $package->name }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $package->category->name }}</span>
                                                </td>
                                                <td>{{ $package->duration_formatted }}</td>
                                                <td>{{ $package->total_questions }}</td>
                                                <td>{{ $package->passing_score }}%</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $package->sessions_count }}</span>
                                                </td>
                                                <td>
                                                    @if($package->is_applicant_flow)
                                                        <div class="d-flex flex-column">
                                                            <span class="badge bg-primary mb-1">{{ __('Applicant Flow') }}</span>
                                                            @if($package->is_screening_test)
                                                                <span class="badge bg-warning">{{ __('Screening Test') }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Order: {{ $package->applicant_flow_order }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge bg-light text-dark">{{ __('General Test') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($package->is_active)
                                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(checkAdminHasPermission('test.package.view'))
                                                            <a href="{{ route('admin.test-package.show', $package) }}" 
                                                            class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.package.edit'))
                                                            <a href="{{ route('admin.test-package.edit', $package) }}" 
                                                            class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.question.view'))
                                                            <a href="{{ route('admin.test-question.index', ['package_id' => $package->id]) }}" 
                                                            class="btn btn-sm btn-primary">
                                                                <i class="fas fa-question-circle"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.package.view'))
                                                            <button class="btn btn-sm btn-success" 
                                                                    onclick="window.generateTestLink({{ $package->id }})" 
                                                                    title="{{ __('Generate Test Link & QR Code') }}">
                                                                <i class="fas fa-link"></i>
                                                            </button>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.package.view'))
                                                            <button class="btn btn-sm btn-info" 
                                                                    onclick="window.generatePublicPackageLink({{ $package->id }})" 
                                                                    title="{{ __('Generate Public Package Link') }}">
                                                                <i class="fas fa-share-alt"></i>
                                                            </button>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.session.view'))
                                                            <a href="{{ route('admin.test-session.index', ['package_id' => $package->id]) }}" 
                                                               class="btn btn-sm btn-secondary" 
                                                               title="{{ __('View Test Sessions') }}">
                                                                <i class="fas fa-list-alt"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.package.create'))
                                                            <form action="{{ route('admin.test-package.duplicate', $package) }}" 
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('{{ __('Are you sure you want to duplicate this package?') }}')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                                        title="{{ __('Duplicate Package') }}">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.package.delete'))
                                                            <form action="{{ route('admin.test-package.destroy', $package) }}" 
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this package?') }}')">
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
                                                <td colspan="9" class="text-center">{{ __('No packages found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $packages->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Test Link Modal -->
<div id="testLinkModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Test Link & QR Code') }}</h3>
            <span class="close" onclick="closeTestLinkModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <div id="testLinkContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Public Package Link Modal -->
<div id="publicPackageLinkModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Public Package Link') }}</h3>
            <span class="close" onclick="closePublicPackageLinkModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <div id="publicPackageLinkContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
window.generateTestLink = function(packageId) {
    // Show loading
    document.getElementById('testLinkContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> {{ __("Generating test link...") }}</div>';
    document.getElementById('testLinkModal').style.display = 'block';
    
    fetch(`/admin/test-package/${packageId}/generate-test-link`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('testLinkContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ __('Test URL') }}</h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="testUrl" value="${data.test_url}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('testUrl')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-muted small">
                            <i class="fas fa-clock"></i> {{ __('Expires at') }}: ${data.expires_at}
                        </p>
                        <a href="${data.test_url}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> {{ __('Open Test') }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h5>{{ __('QR Code') }}</h5>
                        <div class="text-center">
                            <img src="${data.qr_code}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                        </div>
                        <p class="text-muted small text-center mt-2">
                            {{ __('Scan to access test') }}
                        </p>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i>
                    {{ __('This test link is valid for 24 hours. Users must login first before accessing the test.') }}
                </div>
            `;
        } else {
            document.getElementById('testLinkContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('testLinkContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('Error generating test link. Please try again.') }}
            </div>
        `;
    });
}

window.closeTestLinkModal = function() {
    document.getElementById('testLinkModal').style.display = 'none';
}

window.generatePublicPackageLink = function(packageId) {
    // Show loading
    document.getElementById('publicPackageLinkContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> {{ __("Generating public package link...") }}</div>';
    document.getElementById('publicPackageLinkModal').style.display = 'block';
    
    fetch(`/admin/test-package/${packageId}/generate-public-link`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('publicPackageLinkContent').innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ __('Public Package URL') }}</h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="publicPackageUrl" value="${data.public_url}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('publicPackageUrl')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-muted small">
                            <i class="fas fa-share-alt"></i> {{ __('This link can be shared publicly. Users will need to login to take the test.') }}
                        </p>
                        <a href="${data.public_url}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> {{ __('Open Public Package') }}
                        </a>
                    </div>
                    <div class="col-md-4">
                        <h5>{{ __('QR Code') }}</h5>
                        <div class="text-center">
                            <img src="${data.qr_code}" alt="QR Code" style="max-width: 200px; height: auto;">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('This public package link can be shared with anyone. When users click the link, they will see the package information and can login to take the test.') }}
                    </div>
                </div>
            `;
        } else {
            document.getElementById('publicPackageLinkContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('publicPackageLinkContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('Error generating public package link. Please try again.') }}
            </div>
        `;
    });
}

window.closePublicPackageLinkModal = function() {
    document.getElementById('publicPackageLinkModal').style.display = 'none';
}

window.copyToClipboard = function(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        // Show success message
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('{{ __("Failed to copy to clipboard") }}');
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('testLinkModal');
    if (event.target == modal) {
        closeTestLinkModal();
    }
}

// Export functionality
function exportData(type) {
    // Get current filter parameters
    const categoryId = document.getElementById('category_id').value;
    const status = document.getElementById('status').value;
    const applicantFlow = document.getElementById('applicant_flow').value;
    
    // Build export URL with current filters
    let exportUrl = '';
    if (type === 'excel') {
        exportUrl = '{{ route("admin.test-package.export-excel") }}';
    } else if (type === 'pdf') {
        exportUrl = '{{ route("admin.test-package.export-pdf") }}';
    }
    
    // Add filter parameters to URL
    const params = new URLSearchParams();
    if (categoryId) params.append('category_id', categoryId);
    if (status !== '') params.append('status', status);
    if (applicantFlow !== '') params.append('applicant_flow', applicantFlow);
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Open export URL in new window
    window.open(exportUrl, '_blank');
}
</script>
@endpush
