@extends('admin.master_layout')

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit Branch') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.branch.index') }}">{{ __('Branch') }}</a></div>
                <div class="breadcrumb-item">{{ __('Edit') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Branch Information') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.branch.update', $branch) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_id">{{ __('Service') }} <span class="text-danger">*</span></label>
                                            <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                                <option value="">{{ __('Select Service') }}</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" {{ (old('service_id', $branch->service_id) == $service->id) ? 'selected' : '' }}>
                                                        {{ $service->translation?->title ?? 'Service #' . $service->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('service_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Branch Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $branch->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $branch->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">{{ __('City') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" 
                                                   value="{{ old('city', $branch->city) }}" required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province">{{ __('Province') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="province" id="province" class="form-control @error('province') is-invalid @enderror" 
                                                   value="{{ old('province', $branch->province) }}" required>
                                            @error('province')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="map">{{ __('Map Link/Embed') }}</label>
                                    <textarea name="map" id="map" rows="3" class="form-control @error('map') is-invalid @enderror" 
                                              placeholder="{{ __('Enter Google Maps embed code or link') }}">{{ old('map', $branch->map) }}</textarea>
                                    @error('map')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('You can paste Google Maps embed code or share link here') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $branch->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order">{{ __('Order') }}</label>
                                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                                                   value="{{ old('order', $branch->order) }}" min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('Lower number will be displayed first') }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="hidden" name="status" value="0">
                                            <div class="form-check">
                                                <input type="checkbox" name="status" id="status" class="form-check-input" 
                                                       value="1" {{ old('status', $branch->status) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">
                                                    {{ __('Active') }}
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Check to make this branch active') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Update Branch') }}
                                    </button>
                                    <a href="{{ route('admin.branch.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                    </a>
                                </div>
                            </form>
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
        document.addEventListener('DOMContentLoaded', function() {
        const textareas = document.querySelectorAll('textarea.form-control');
        
        textareas.forEach(function(textarea) {
            // Only auto-resize on initial load to fit content
            if (textarea.value.trim()) {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }
        });
    });

</script>

@endpush