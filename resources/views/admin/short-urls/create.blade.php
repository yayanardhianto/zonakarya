@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Short URL') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Create Short URL') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Short URLs') => route('admin.short-urls.index'),
            __('Create') => '#',
        ]" />

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Create New Short URL') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.short-urls.store') }}" method="POST">
                        @csrf

                        <!-- Original URL -->
                        <div class="form-group mb-3">
                            <label for="original_url" class="form-label">{{ __('Original URL') }} <span class="text-danger">*</span></label>
                            <input 
                                type="url" 
                                class="form-control @error('original_url') is-invalid @enderror" 
                                id="original_url" 
                                name="original_url"
                                value="{{ old('original_url') }}"
                                placeholder="https://example.com/long/url/here"
                                required>
                            @error('original_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('Enter the full URL you want to shorten') }}</small>
                        </div>

                        <!-- Code Type Selection -->
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Short Code Type') }} <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    id="code_type_random" 
                                    name="code_type" 
                                    value="random"
                                    checked
                                    onchange="toggleCodeInput()">
                                <label class="form-check-label" for="code_type_random">
                                    {{ __('Random Code (Auto-generated)') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    id="code_type_custom" 
                                    name="code_type" 
                                    value="custom"
                                    onchange="toggleCodeInput()">
                                <label class="form-check-label" for="code_type_custom">
                                    {{ __('Custom Code') }}
                                </label>
                            </div>
                        </div>

                        <!-- Custom Code Input (hidden by default) -->
                        <div class="form-group mb-3" id="custom_code_group" style="display: none;">
                            <label for="code" class="form-label">{{ __('Short Code') }}</label>
                            <input 
                                type="text" 
                                class="form-control @error('code') is-invalid @enderror" 
                                id="code" 
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="my-custom-code"
                                maxlength="50">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ __('Use only letters, numbers, hyphens, and underscores. Must be unique.') }}
                            </small>
                        </div>

                        <!-- Preview -->
                        <div class="alert alert-info" id="preview_box" style="display: none;">
                            <strong>{{ __('Preview:') }}</strong>
                            <br>
                            <code id="preview_url">{{ config('app.url') }}/your-code</code>
                            <button type="button" class="btn btn-sm btn-outline-primary bg-white ms-2" onclick="copyToClipboard()">
                                <i class="fas fa-copy me-1"></i>{{ __('Copy') }}
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Short URL') }}
                            </button>
                            <a href="{{ route('admin.short-urls.index') }}" class="btn btn-dark">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function toggleCodeInput() {
    const codeType = document.querySelector('input[name="code_type"]:checked').value;
    const customCodeGroup = document.getElementById('custom_code_group');
    const codeInput = document.getElementById('code');
    const previewBox = document.getElementById('preview_box');

    if (codeType === 'custom') {
        customCodeGroup.style.display = 'block';
        codeInput.required = true;
        previewBox.style.display = 'block';
        updatePreview();
    } else {
        customCodeGroup.style.display = 'none';
        codeInput.required = false;
        previewBox.style.display = 'none';
    }
}

function updatePreview() {
    const code = document.getElementById('code').value || 'your-code';
    const preview = `{{ config('app.url') }}/${code}`;
    document.getElementById('preview_url').textContent = preview;
}

function copyToClipboard() {
    const preview = document.getElementById('preview_url').textContent;
    navigator.clipboard.writeText(preview).then(() => {
        alert('{{ __("Copied to clipboard!") }}');
    });
}

// Update preview as user types
document.getElementById('code')?.addEventListener('input', updatePreview);
</script>
@endsection
