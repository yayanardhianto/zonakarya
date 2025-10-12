@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create WhatsApp Template') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Create WhatsApp Template') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('WhatsApp Templates') => route('admin.whatsapp-templates.index'),
            __('Create') => '#',
        ]" />

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Create WhatsApp Template') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.whatsapp-templates.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Template Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">{{ __('Template Type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required onchange="updateVariables()">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="template">{{ __('Template Message') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="template" name="template" rows="6" 
                                      placeholder="{{ __('Enter your WhatsApp message template...') }}" required>{{ old('template') }}</textarea>
                            <small class="form-text text-muted">
                                {{ __('Use variables like {NAME}, {POSITION}, {COMPANY}, etc.') }}
                            </small>
                            @error('template')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>{{ __('Available Variables') }}</label>
                            <div id="availableVariables" class="variables-container">
                                <p class="text-muted">{{ __('Select a template type to see available variables') }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('Create Template') }}
                            </button>
                            <a href="{{ route('admin.whatsapp-templates.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
const defaultVariables = @json($defaultVariables);

function updateVariables() {
    const type = document.getElementById('type').value;
    const container = document.getElementById('availableVariables');
    
    if (type && defaultVariables[type]) {
        let html = '<div class="row">';
        defaultVariables[type].forEach(variable => {
            html += `
                <div class="col-md-3 mb-2">
                    <span class="badge badge-info variable-badge" onclick="insertVariable('{${variable}}')" 
                          style="cursor: pointer;">{${variable}}</span>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    } else {
        container.innerHTML = '<p class="text-muted">{{ __("Select a template type to see available variables") }}</p>';
    }
}

function insertVariable(variable) {
    const textarea = document.getElementById('template');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const before = text.substring(0, start);
    const after = text.substring(end, text.length);
    
    textarea.value = before + variable + after;
    textarea.focus();
    textarea.setSelectionRange(start + variable.length, start + variable.length);
}

// Initialize variables on page load
document.addEventListener('DOMContentLoaded', function() {
    updateVariables();
});
</script>

<style>
.variable-badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
}

.variable-badge:hover {
    background-color: #17a2b8 !important;
    transform: scale(1.05);
}
</style>
@endpush
