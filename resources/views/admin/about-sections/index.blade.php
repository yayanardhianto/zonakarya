@extends('admin.master_layout')

@section('title', 'About Page Sections')

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __(' About Page Sections Management') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __(' About Page Sections Management') }}</div>
            </div>
        </div>
        
        <!-- Edit About Page Title Button -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ __('About Page Title') }}</h5>
                                <p class="text-muted mb-0">{{ __('Current title:') }} <strong id="current-title">{{ $setting?->about_page_title ?? __('About') }}</strong></p>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTitleModal">
                                <i class="fas fa-edit"></i> {{ __('Edit Title') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Instructions:</strong> Drag and drop sections to reorder them. Toggle the switch to show/hide sections on the About page.
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="sectionsTable">
                                            <thead>
                                                <tr>
                                                    <th width="5%">Order</th>
                                                    <th width="30%">Section Name</th>
                                                    <th width="40%">Description</th>
                                                    <th width="15%">Status</th>
                                                    <th width="10%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sortableSections">
                                                @foreach($sections as $section)
                                                <tr data-id="{{ $section->id }}" class="sortable-row">
                                                    <td>
                                                        <div class="drag-handle">
                                                            <i class="fas fa-grip-vertical text-muted"></i>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @switch($section->name)
                                                            @case('award_section')
                                                                <strong>Timeline Section</strong>
                                                                @break
                                                            @default
                                                                <strong>{{ ucwords(str_replace('_', ' ', $section->name)) }}</strong>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @switch($section->name)
                                                            @case('counter_section')
                                                                <span class="badge badge-info">Statistics counter with numbers and descriptions</span>
                                                                @break
                                                            @case('choose_us_section')
                                                                <span class="badge badge-primary">Why choose us section with image and content</span>
                                                                @break
                                                            @case('award_section')
                                                                <span class="badge badge-warning">Timeline and achievements showcase</span>
                                                                @break
                                                            @case('team_section')
                                                                <span class="badge badge-success">Team members showcase</span>
                                                                @break
                                                            @case('contact_section')
                                                                <span class="badge badge-danger">Contact form and map</span>
                                                                @break
                                                            @case('brand_section')
                                                                <span class="badge badge-secondary">Client brands and partners</span>
                                                                @break
                                                            @default
                                                                <span class="badge badge-light">Custom section</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input section-toggle" 
                                                                type="checkbox" 
                                                                data-section-id="{{ $section->id }}"
                                                                {{ $section->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            @switch($section->name)
                                                                @case('counter_section')
                                                                    <a href="{{ route('admin.counter-section.index', ['code' => 'en']) }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Counter Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @case('choose_us_section')
                                                                    <a href="{{ route('admin.choose-us-section.index', ['code' => 'en']) }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Choose Us Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @case('award_section')
                                                                    <a href="{{ route('admin.award.index') }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Award Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @case('team_section')
                                                                    <a href="{{ route('admin.ourteam.index') }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Team Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @case('contact_section')
                                                                    <a href="{{ route('admin.contact-section.index') }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Contact Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @case('brand_section')
                                                                    <a href="{{ route('admin.brand.index') }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Edit Brand Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    @break
                                                                @default
                                                                    <button type="button" 
                                                                            class="btn btn-sm btn-outline-primary"
                                                                            onclick="editSection({{ $section->id }})"
                                                                            title="Edit Section">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                            @endswitch
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

<!-- Edit Title Modal -->
<div class="modal fade" id="editTitleModal" tabindex="-1" aria-labelledby="editTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTitleModalLabel">{{ __('Edit About Page Title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTitleForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="aboutTitle" class="form-label">{{ __('About Page Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="aboutTitle" name="title" value="{{ $setting?->about_page_title ?? __('About') }}" required>
                        <div class="form-text">{{ __('This title will appear in the breadcrumb section of the About page.') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable
    const sortable = Sortable.create(document.getElementById('sortableSections'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            updateSectionOrder();
        }
    });

    // Toggle section status
    document.querySelectorAll('.section-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const sectionId = this.dataset.sectionId;
            toggleSectionStatus(sectionId);
        });
    });

    // Handle edit title form submission
    document.getElementById('editTitleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateAboutTitle();
    });
});

function updateSectionOrder() {
    const rows = document.querySelectorAll('#sortableSections tr');
    const sections = [];
    
    rows.forEach((row, index) => {
        sections.push({
            id: row.dataset.id,
            order: index + 1
        });
    });

    fetch('{{ route("admin.about-sections.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sections: sections })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Section order updated successfully', 'success');
        } else {
            showNotification('Failed to update section order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating order', 'error');
    });
}

function toggleSectionStatus(sectionId) {
    fetch(`{{ url('admin/about-sections') }}/${sectionId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toggle = document.querySelector(`[data-section-id="${sectionId}"]`);
            const label = toggle.nextElementSibling;
            label.textContent = data.is_active ? 'Active' : 'Inactive';
            showNotification('Section status updated successfully', 'success');
        } else {
            showNotification('Failed to update section status', 'error');
            // Revert toggle state
            const toggle = document.querySelector(`[data-section-id="${sectionId}"]`);
            toggle.checked = !toggle.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating status', 'error');
        // Revert toggle state
        const toggle = document.querySelector(`[data-section-id="${sectionId}"]`);
        toggle.checked = !toggle.checked;
    });
}

function editSection(sectionId) {
    // Redirect to section edit page or open modal
    window.location.href = `{{ url('admin/sections') }}/${sectionId}/edit`;
}

function updateAboutTitle() {
    const title = document.getElementById('aboutTitle').value.trim();
    
    if (!title) {
        showNotification('Please enter a title', 'error');
        return;
    }

    // Show loading state
    const submitBtn = document.querySelector('#editTitleForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Saving...") }}';
    submitBtn.disabled = true;

    fetch('{{ route("admin.about-sections.update-title") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ title: title })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update current title display
            document.getElementById('current-title').textContent = title;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editTitleModal'));
            modal.hide();
            
            showNotification('About page title updated successfully', 'success');
        } else {
            showNotification(data.message || 'Failed to update title', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating title', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function showNotification(message, type) {
    // Create floating notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        animation: slideInRight 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}
</script>

<style>
.sortable-row {
    cursor: move;
}

.drag-handle {
    cursor: grab;
    padding: 5px;
}

.drag-handle:active {
    cursor: grabbing;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* Floating notification animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>
@endsection
