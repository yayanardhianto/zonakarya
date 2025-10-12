@extends('admin.master_layout')
@section('title', __('Add New Test Package'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Add New Test Package') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Add New Test Package') }}</div>
            </div>
        </div>
        <div class="section-body">        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Add New Test Package') }}</h3>
                                <a href="{{ route('admin.test-package.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Packages') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.test-package.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id" required>
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Package Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">{{ __('Description') }}</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                    id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="duration_minutes" class="form-label">{{ __('Duration (Minutes)') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                                id="duration_minutes" name="duration_minutes" 
                                                value="{{ old('duration_minutes') }}" min="1" required>
                                            @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="passing_score" class="form-label">{{ __('Passing Score (%)') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                                id="passing_score" name="passing_score" 
                                                value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                                            @error('passing_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <!-- Question Order Settings -->
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Question Order') }}</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="randomize_questions" name="randomize_questions" 
                                                    value="1" {{ old('randomize_questions') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="randomize_questions">
                                                    {{ __('Randomize Question Order') }}
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                {{ __('Questions will be shown in random order to each test taker. You can change this later in package management.') }}
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="show_score_to_user" class="form-label">{{ __('Show Score to User') }}</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="show_score_to_user" name="show_score_to_user" 
                                                    value="1" {{ old('show_score_to_user') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_score_to_user">
                                                    {{ __('Show score to user after test completion') }}
                                                </label>
                                            </div>
                                            <small class="text-muted">{{ __('Only applicable for multiple choice questions') }}</small>
                                            @error('show_score_to_user')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="is_applicant_flow" class="form-label">{{ __('Applicant Flow') }}</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="is_applicant_flow" name="is_applicant_flow" 
                                                    value="1" {{ old('is_applicant_flow') ? 'checked' : '' }}
                                                    onchange="toggleApplicantFlowFields()">
                                                <label class="form-check-label" for="is_applicant_flow">
                                                    {{ __('Include in Applicant Flow') }}
                                                </label>
                                            </div>
                                            <small class="text-muted">{{ __('Check if this test package is part of the applicant recruitment process') }}</small>
                                            @error('is_applicant_flow')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div id="applicant-flow-fields" style="display: {{ old('is_applicant_flow') ? 'block' : 'none' }}">
                                            <div class="mb-3">
                                                <label for="is_screening_test" class="form-label">{{ __('Test Type') }}</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        id="is_screening_test" name="is_screening_test" 
                                                        value="1" {{ old('is_screening_test') ? 'checked' : '' }}
                                                        onchange="toggleScreeningTest()">
                                                    <label class="form-check-label" for="is_screening_test">
                                                        {{ __('Screening Test') }}
                                                    </label>
                                                </div>
                                                <small class="text-muted">{{ __('Check if this is the initial screening test for applicants') }}</small>
                                                @error('is_screening_test')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div id="flow-order-field" style="display: {{ old('is_screening_test') ? 'none' : 'block' }}">
                                                <div class="mb-3">
                                                    <label for="applicant_flow_order" class="form-label">{{ __('Flow Order') }}</label>
                                                    <input type="number" class="form-control @error('applicant_flow_order') is-invalid @enderror" 
                                                        id="applicant_flow_order" name="applicant_flow_order" 
                                                        value="{{ old('applicant_flow_order') }}" 
                                                        min="1" max="10" placeholder="Enter order (1, 2, 3, etc.)">
                                                    <small class="text-muted">{{ __('Order of this test in the applicant flow (1 = first, 2 = second, etc.)') }}</small>
                                                    @error('applicant_flow_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">{{ __('Status') }}</label>
                                            <select class="form-select @error('is_active') is-invalid @enderror" 
                                                    id="is_active" name="is_active">
                                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                                                    {{ __('Inactive') }}
                                                </option>
                                            </select>
                                            @error('is_active')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Create Package') }}
                                    </button>
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
function toggleApplicantFlowFields() {
    const isApplicantFlow = document.getElementById('is_applicant_flow').checked;
    const applicantFlowFields = document.getElementById('applicant-flow-fields');
    
    if (isApplicantFlow) {
        applicantFlowFields.style.display = 'block';
    } else {
        applicantFlowFields.style.display = 'none';
        // Reset screening test checkbox when applicant flow is disabled
        document.getElementById('is_screening_test').checked = false;
        toggleScreeningTest();
    }
}

function toggleScreeningTest() {
    const isScreeningTest = document.getElementById('is_screening_test').checked;
    const flowOrderField = document.getElementById('flow-order-field');
    
    if (isScreeningTest) {
        flowOrderField.style.display = 'none';
        // Clear flow order when screening test is selected
        document.getElementById('applicant_flow_order').value = '';
    } else {
        flowOrderField.style.display = 'block';
    }
}
</script>
@endpush
