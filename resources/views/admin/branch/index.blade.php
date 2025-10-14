@extends('admin.master_layout')

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Branch Management') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Branch') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('All Branches') }}</h4>
                            @if(checkAdminHasPermission('branch.create'))
                                <div class="card-header-action">
                                    <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#editWordingModal">
                                        <i class="fas fa-edit"></i> {{ __('Edit Wording') }}
                                    </button>
                                    <a href="{{ route('admin.branch.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('Add New Branch') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.branch.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="service_id" class="form-control">
                                            <option value="">{{ __('All Services') }}</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->translation?->title ?? $service->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="">{{ __('All Status') }}</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="keyword" class="form-control" placeholder="{{ __('Search...') }}" value="{{ request('keyword') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                                        <a href="{{ route('admin.branch.index') }}" class="btn btn-secondary">{{ __('Clear') }}</a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Service') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('City') }}</th>
                                            <th>{{ __('Province') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($branches as $branch)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $branch->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $branch->service->translation?->title ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($branch->address, 50) }}</td>
                                                <td>{{ $branch->city }}</td>
                                                <td>{{ $branch->province }}</td>
                                                <td>
                                                    @if(checkAdminHasPermission('branch.edit'))
                                                        <label class="custom-switch mt-2">
                                                            <input type="checkbox" name="custom-switch-checkbox" 
                                                                   class="custom-switch-input" 
                                                                   {{ $branch->status ? 'checked' : '' }}
                                                                   onchange="changeStatus({{ $branch->id }}, this.checked)">
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                    @else
                                                        <span class="badge {{ $branch->status ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $branch->status ? __('Active') : __('Inactive') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $branch->order }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(checkAdminHasPermission('branch.view'))
                                                            <a href="{{ route('admin.branch.show', $branch) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('branch.edit'))
                                                            <a href="{{ route('admin.branch.edit', $branch) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('branch.delete'))
                                                            <form action="{{ route('admin.branch.destroy', $branch) }}" method="POST" class="d-inline"
                                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this branch?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">{{ __('No branches found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($branches instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-center">
                                    {{ $branches->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('js')
<script>
function changeStatus(id, status) {
    fetch(`{{ url('admin/branch-status') }}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status ? 1 : 0 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
        } else {
            toastr.error('Something went wrong!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Something went wrong!');
    });
}
</script>

<!-- Edit Wording Modal -->
<div class="modal fade" id="editWordingModal" tabindex="-1" aria-labelledby="editWordingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWordingModalLabel">
                    <i class="fas fa-edit"></i> {{ __('Edit Section Wording') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="wordingForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ __('Note:') }}</strong> 
                        {{ __('These settings control the wording displayed in the contact us area section on the homepage.') }}
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_section_title">{{ __('Section Title') }}</label>
                        <input type="text" name="section_title" id="modal_section_title" 
                               class="form-control" 
                               placeholder="{{ __('Enter section title') }}">
                        <small class="form-text text-muted">{{ __('This will be displayed as the main title of the contact us area section') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="modal_section_description">{{ __('Section Description') }}</label>
                        <textarea name="section_description" id="modal_section_description" rows="3" 
                                  class="form-control" 
                                  placeholder="{{ __('Enter section description') }}"></textarea>
                        <small class="form-text text-muted">{{ __('This will be displayed as the description below the section title') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveWordingBtn">
                        <i class="fas fa-save"></i> {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load current wording when modal opens
    document.getElementById('editWordingModal').addEventListener('show.bs.modal', function() {
        loadCurrentWording();
    });

    // Handle form submission
    document.getElementById('wordingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveWording();
    });
});

function loadCurrentWording() {
    fetch('{{ route("admin.branch.get-wording") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal_section_title').value = data.section_title || '';
            document.getElementById('modal_section_description').value = data.section_description || '';
        }
    })
    .catch(error => {
        console.error('Error loading wording:', error);
        toastr.error('Failed to load current wording');
    });
}

function saveWording() {
    const saveBtn = document.getElementById('saveWordingBtn');
    const originalText = saveBtn.innerHTML;
    
    // Show loading state
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Saving...") }}';
    saveBtn.disabled = true;

    const formData = new FormData();
    formData.append('section_title', document.getElementById('modal_section_title').value);
    formData.append('section_description', document.getElementById('modal_section_description').value);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("admin.branch.update-wording") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message || 'Wording updated successfully!');
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editWordingModal'));
            modal.hide();
        } else {
            toastr.error(data.message || 'Failed to update wording');
        }
    })
    .catch(error => {
        console.error('Error saving wording:', error);
        toastr.error('Something went wrong while saving');
    })
    .finally(() => {
        // Restore button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}
</script>
@endpush
@endsection
