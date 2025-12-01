@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Short URL') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Edit Short URL') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Short URLs') => route('admin.short-urls.index'),
            __('Edit') => '#',
        ]" />

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Edit Short URL') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.short-urls.update', $shortUrl) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Short Code (Read-only) -->
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">{{ __('Short Code') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ config('app.url') }}/</span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="code" 
                                    value="{{ $shortUrl->code }}"
                                    readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="copyLink()">
                                    <i class="fas fa-copy me-1"></i>{{ __('Copy') }}
                                </button>
                            </div>
                            <small class="form-text text-muted">{{ __('Short code cannot be changed') }}</small>
                        </div>

                        <!-- Original URL -->
                        <div class="form-group mb-3">
                            <label for="original_url" class="form-label">{{ __('Original URL') }} <span class="text-danger">*</span></label>
                            <input 
                                type="url" 
                                class="form-control @error('original_url') is-invalid @enderror" 
                                id="original_url" 
                                name="original_url"
                                value="{{ old('original_url', $shortUrl->original_url) }}"
                                required>
                            @error('original_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statistics -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>{{ __('Total Clicks:') }}</strong>
                                    <h3 class="mb-0">{{ $shortUrl->click_count }}</h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning">
                                    <strong>{{ __('Created:') }}</strong>
                                    <p class="mb-0">{{ $shortUrl->created_at->format('d M Y H:i:s') }}</p>
                                    <small>{{ __('by') }} {{ $shortUrl->creator?->name ?? '-' }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Update Short URL') }}
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
function copyLink() {
    const url = `{{ config('app.url') }}/{{ $shortUrl->code }}`;
    navigator.clipboard.writeText(url).then(() => {
        alert('{{ __("Short URL copied to clipboard!") }}');
    });
}
</script>
@endsection
