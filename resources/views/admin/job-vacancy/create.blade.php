@extends('admin.master_layout')
@section('title', __('Create Job Vacancy'))

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Job Vacancy') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.job-vacancy.index') }}">{{ __('Job Vacancies') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Create') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Job Information') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.job-vacancy.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Position') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                                       name="position" value="{{ old('position') }}" required>
                                                @error('position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Location') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('location_id') is-invalid @enderror" name="location_id" required>
                                                    <option value="">{{ __('Select Location') }}</option>
                                                    @foreach($locations as $location)
                                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                            {{ $location->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('location_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Work Type') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('work_type') is-invalid @enderror" name="work_type" required>
                                                    <option value="">{{ __('Select Work Type') }}</option>
                                                    <option value="Full-Time" {{ old('work_type') == 'Full-Time' ? 'selected' : '' }}>{{ __('Full-Time') }}</option>
                                                    <option value="Part-Time" {{ old('work_type') == 'Part-Time' ? 'selected' : '' }}>{{ __('Part-Time') }}</option>
                                                    <option value="Contract" {{ old('work_type') == 'Contract' ? 'selected' : '' }}>{{ __('Contract') }}</option>
                                                    <option value="Freelance" {{ old('work_type') == 'Freelance' ? 'selected' : '' }}>{{ __('Freelance') }}</option>
                                                    <option value="Internship" {{ old('work_type') == 'Internship' ? 'selected' : '' }}>{{ __('Internship') }}</option>
                                                </select>
                                                @error('work_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Education') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('education') is-invalid @enderror" name="education" required>
                                                    <option value="">{{ __('Select Education') }}</option>
                                                    <option value="SMA" {{ old('education') == 'SMA' ? 'selected' : '' }}>{{ __('SMA') }}</option>
                                                    <option value="D3" {{ old('education') == 'D3' ? 'selected' : '' }}>{{ __('D3') }}</option>
                                                    <option value="S1" {{ old('education') == 'S1' ? 'selected' : '' }}>{{ __('S1') }}</option>
                                                    <option value="S2" {{ old('education') == 'S2' ? 'selected' : '' }}>{{ __('S2') }}</option>
                                                    <option value="S3" {{ old('education') == 'S3' ? 'selected' : '' }}>{{ __('S3') }}</option>
                                                    <option value="Tidak Ada Persyaratan" {{ old('education') == 'Tidak Ada Persyaratan' ? 'selected' : '' }}>{{ __('No Requirements') }}</option>
                                                </select>
                                                @error('education')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Gender') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                                    <option value="">{{ __('Select Gender') }}</option>
                                                    <option value="Pria" {{ old('gender') == 'Pria' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                                    <option value="Wanita" {{ old('gender') == 'Wanita' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                                    <option value="Semua Jenis" {{ old('gender') == 'Semua Jenis' ? 'selected' : '' }}>{{ __('All Genders') }}</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Salary Min') }}</label>
                                                <input type="number" class="form-control @error('salary_min') is-invalid @enderror" 
                                                       name="salary_min" value="{{ old('salary_min') }}" min="0">
                                                @error('salary_min')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Salary Max') }}</label>
                                                <input type="number" class="form-control @error('salary_max') is-invalid @enderror" 
                                                       name="salary_max" value="{{ old('salary_max') }}" min="0">
                                                @error('salary_max')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Age Min') }}</label>
                                                <input type="number" class="form-control @error('age_min') is-invalid @enderror" 
                                                       name="age_min" value="{{ old('age_min') }}" min="18" max="65">
                                                @error('age_min')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Age Max') }}</label>
                                                <input type="number" class="form-control @error('age_max') is-invalid @enderror" 
                                                       name="age_max" value="{{ old('age_max') }}" min="18" max="65">
                                                @error('age_max')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Visibility Options -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-primary mb-3">{{ __('Frontend Display Options') }}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="hidden" name="show_salary" value="0">
                                                    <input class="form-check-input" type="checkbox" name="show_salary" value="1" 
                                                           id="show_salary" {{ old('show_salary', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="show_salary">
                                                        {{ __('Show Salary Information on Frontend') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('Display salary range on job listing and detail pages') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="hidden" name="show_age" value="0">
                                                    <input class="form-check-input" type="checkbox" name="show_age" value="1" 
                                                           id="show_age" {{ old('show_age', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="show_age">
                                                        {{ __('Show Age Requirements on Frontend') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('Display age requirements on job listing and detail pages') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Experience (Years)') }} <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                                       name="experience_years" value="{{ old('experience_years', 0) }}" min="0" required>
                                                @error('experience_years')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Application Deadline') }}</label>
                                                <input type="date" class="form-control @error('application_deadline') is-invalid @enderror" 
                                                       name="application_deadline" value="{{ old('application_deadline') }}">
                                                @error('application_deadline')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Specific Requirements') }}</label>
                                        <div id="requirements-container">
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="specific_requirements[]" placeholder="{{ __('Enter requirement') }}">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-success" onclick="addRequirement()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">{{ __('Add specific requirements for this position') }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  name="description" rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Responsibilities') }}</label>
                                        <textarea class="form-control @error('responsibilities') is-invalid @enderror" 
                                                  name="responsibilities" rows="4">{{ old('responsibilities') }}</textarea>
                                        @error('responsibilities')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Benefits') }}</label>
                                        <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                                  name="benefits" rows="4">{{ old('benefits') }}</textarea>
                                        @error('benefits')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                            <option value="">{{ __('Select Status') }}</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Create Job Vacancy') }}</button>
                                        <a href="{{ route('admin.job-vacancy.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
    function addRequirement() {
        const container = document.getElementById('requirements-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="specific_requirements[]" placeholder="{{ __('Enter requirement') }}">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger" onclick="removeRequirement(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    function removeRequirement(button) {
        button.closest('.input-group').remove();
    }
</script>
@endpush
