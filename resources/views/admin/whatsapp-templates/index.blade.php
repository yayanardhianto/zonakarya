@extends('admin.master_layout')
@section('title')
    <title>{{ __('WhatsApp Templates') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('WhatsApp Templates') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('WhatsApp Templates') => '#',
        ]" />

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('WhatsApp Templates') }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.whatsapp-templates.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Add Template') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Template Preview') }}</th>
                                    <th>{{ __('Variables') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    <tr>
                                        <td>
                                            <strong>{{ $template->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $template->type == 'short_call_invitation' ? 'success' : 'warning' }}">
                                                {{ ucwords(str_replace('_', ' ', $template->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="template-preview">
                                                {{ Str::limit($template->template, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($template->variables && is_array($template->variables))
                                                @foreach($template->variables as $variable)
                                                    <span class="badge badge-info">{{ $variable }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">{{ __('No variables') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       {{ $template->is_active ? 'checked' : '' }}
                                                       onchange="toggleStatus({{ $template->id }})">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.whatsapp-templates.show', $template) }}" 
                                                   class="btn btn-sm btn-info" title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.whatsapp-templates.edit', $template) }}" 
                                                   class="btn btn-sm btn-primary" title="{{ __('Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteTemplate({{ $template->id }})" 
                                                        title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No templates found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $templates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Delete Template') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this template?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function toggleStatus(templateId) {
    fetch(`/admin/whatsapp-templates/${templateId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Status updated successfully
        } else {
            alert('Error updating template status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating template status');
    });
}

function deleteTemplate(templateId) {
    document.getElementById('deleteForm').action = `/admin/whatsapp-templates/${templateId}`;
    $('#deleteModal').modal('show');
}
</script>
@endpush
