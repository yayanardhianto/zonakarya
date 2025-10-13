@extends('admin.master_layout')
@section('title', __('Add New Test Question'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Add Question') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Add Question') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Add Question') }}</h3>
                                <a href="{{ route('admin.test-question.index', ['package_id' => $packageId]) }}" class="btn btn-warning">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Questions') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.test-question.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            {{ __('After creating the question, you can add it to packages from the package management page.') }}
                                        </div>

                                        <div class="mb-3">
                                            <label for="question_text" class="form-label">{{ __('Question Text') }} <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                                    id="question_text" name="question_text" rows="4" required>{{ old('question_text') }}</textarea>
                                            @error('question_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="question_image" class="form-label">{{ __('Question Image') }}</label>
                                            <input type="file" class="form-control @error('question_image') is-invalid @enderror" 
                                                id="question_image" name="question_image" accept="image/*">
                                            <div class="form-text">{{ __('Optional: Upload an image to accompany the question') }}</div>
                                            @error('question_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="question_type" class="form-label">{{ __('Question Type') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('question_type') is-invalid @enderror" 
                                                    id="question_type" name="question_type" required>
                                                <option value="">{{ __('Select Type') }}</option>
                                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>
                                                    {{ __('Multiple Choice') }}
                                                </option>
                                                <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>
                                                    {{ __('Essay') }}
                                                </option>
                                                <option value="scale" {{ old('question_type') == 'scale' ? 'selected' : '' }}>
                                                    {{ __('Scale (1-10)') }}
                                                </option>
                                                <option value="video_record" {{ old('question_type') == 'video_record' ? 'selected' : '' }}>
                                                    {{ __('Video Record') }}
                                                </option>
                                                <option value="forced_choice" {{ old('question_type') == 'forced_choice' ? 'selected' : '' }}>
                                                    {{ __('Forced Choice (Ranking)') }}
                                                </option>
                                            </select>
                                            @error('question_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="points" class="form-label">{{ __('Points') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                                id="points" name="points" value="{{ old('points', 1) }}" min="1" required>
                                            @error('points')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3" style="display: none;">
                                            <label for="order" class="form-label">{{ __('Order') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                                id="order" name="order" value="{{ old('order', 1) }}" min="1" required>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Multiple Choice Options -->
                                <div id="options-section" class="question-type-section" style="display: block; background: #f8f9fa; padding: 15px; border: 2px dashed #007bff; margin: 10px 0;">
                                    <hr>
                                    <h5>{{ __('Answer Options') }}</h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('For multiple choice questions, you can add multiple options and mark one or more as correct answers.') }}
                                    </div>
                                    <div id="options-container">
                                        <!-- Options will be added dynamically -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="add-option">
                                        <i class="fas fa-plus"></i> {{ __('Add Option') }}
                                    </button>
                                </div>

                                <!-- Scale Question Info -->
                                <div id="scale-section" style="display: none;">
                                    <hr>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('Scale questions allow respondents to rate from 1 to 10. No additional options needed.') }}
                                    </div>
                                </div>

                                <!-- Forced Choice Question Info -->
                                <div id="forced-choice-section" class="question-type-section" style="display: none; background: #f8f9fa; padding: 15px; border: 2px dashed #ffc107; margin: 10px 0;">
                                    <hr>
                                    <h5>{{ __('Forced Choice Configuration') }}</h5>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('For forced choice questions:') }}
                                        <ul class="mb-0 mt-2">
                                            <li>{{ __('Enter the instruction in the "Question Text" field above') }}</li>
                                            <li>{{ __('Enter the traits that respondents will choose from below') }}</li>
                                            <li>{{ __('Respondents will select one as "most similar" and one as "least similar" to themselves') }}</li>
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <label for="traits_input" class="form-label">{{ __('Traits (one per line)') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('traits') is-invalid @enderror" 
                                                id="traits_input" name="traits" rows="6" 
                                                placeholder="Enter each trait on a new line, for example:&#10;Gampangan, Mudah Setuju&#10;Percaya, Mudah Percaya Pada Orang Lain&#10;Petualang, Mengambil Resiko&#10;Toleran, Menghormati">{{ old('traits') }}</textarea>
                                        <div class="form-text">{{ __('Enter 4-6 traits that respondents will choose from. Each trait should be on a separate line.') }}</div>
                                        @error('traits')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Create Question') }}
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

    @push('css')
    <style>
        .question-type-section {
            transition: all 0.3s ease;
        }
        
        .question-type-section.show {
            display: block !important;
        }
        
        .question-type-section.hide {
            display: none !important;
        }
    </style>
    @endpush

    @push('js')
    <script>
    console.log('=== SCRIPT LOADING ===');

    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM LOADED - TEST QUESTION CREATE ===');
        
        // Get elements
        const questionTypeSelect = document.getElementById('question_type');
        const optionsSection = document.getElementById('options-section');
        const scaleSection = document.getElementById('scale-section');
        const forcedChoiceSection = document.getElementById('forced-choice-section');
        const optionsContainer = document.getElementById('options-container');
        const addOptionBtn = document.getElementById('add-option');
        const questionTextLabel = document.getElementById('question_text_label');
        const questionTextHelp = document.getElementById('question_text_help');
        
        console.log('Elements found:', {
            questionTypeSelect: !!questionTypeSelect,
            optionsSection: !!optionsSection,
            scaleSection: !!scaleSection,
            forcedChoiceSection: !!forcedChoiceSection,
            optionsContainer: !!optionsContainer,
            addOptionBtn: !!addOptionBtn
        });

        let optionCount = 0;

        // Function to show/hide sections
        function toggleSections() {
            const value = questionTypeSelect ? questionTypeSelect.value : '';
            console.log('Question type selected:', value);
            
            if (value === 'multiple_choice') {
                console.log('SHOWING MULTIPLE CHOICE OPTIONS');
                if (optionsSection) {
                    optionsSection.style.display = 'block';
                    console.log('Options section is now visible');
                }
                if (scaleSection) {
                    scaleSection.style.display = 'none';
                }
                
                // Add default options
                if (optionsContainer && optionsContainer.children.length === 0) {
                    console.log('Adding default options');
                    addOption();
                    addOption();
                }
            } else if (value === 'scale') {
                console.log('SHOWING SCALE SECTION');
                if (optionsSection) {
                    optionsSection.style.display = 'none';
                }
                if (scaleSection) {
                    scaleSection.style.display = 'block';
                }
                if (forcedChoiceSection) {
                    forcedChoiceSection.style.display = 'none';
                }
            } else if (value === 'forced_choice') {
                console.log('SHOWING FORCED CHOICE SECTION');
                if (optionsSection) {
                    optionsSection.style.display = 'none';
                }
                if (scaleSection) {
                    scaleSection.style.display = 'none';
                }
                if (forcedChoiceSection) {
                    forcedChoiceSection.style.display = 'block';
                }
                // Update label and help text for forced choice
                if (questionTextLabel) {
                    questionTextLabel.textContent = '{{ __("Instruction Text") }} *';
                }
                if (questionTextHelp) {
                    questionTextHelp.textContent = '{{ __("Enter the instruction that will be shown to respondents for this forced choice question.") }}';
                }
            } else {
                console.log('HIDING ALL SECTIONS');
                if (optionsSection) {
                    optionsSection.style.display = 'none';
                }
                if (scaleSection) {
                    scaleSection.style.display = 'none';
                }
                if (forcedChoiceSection) {
                    forcedChoiceSection.style.display = 'none';
                }
                // Reset label and help text
                if (questionTextLabel) {
                    questionTextLabel.textContent = '{{ __("Question Text") }} *';
                }
                if (questionTextHelp) {
                    questionTextHelp.textContent = '{{ __("Enter the question text or instruction for this question.") }}';
                }
            }
        }

        // Function to add option
        function addOption() {
            optionCount++;
            console.log('Adding option number:', optionCount);
            
            const optionDiv = document.createElement('div');
            optionDiv.className = 'row mb-3 p-3 border rounded';
            optionDiv.innerHTML = `
                <div class="col-md-1 d-flex align-items-center">
                    <span class="badge bg-primary">${optionCount}</span>
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Option Text</label>
                    <input type="text" class="form-control" name="options[${optionCount}][option_text]" 
                           placeholder="Enter option text" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Correct Answer</label>
                    <select class="form-select" name="options[${optionCount}][is_correct]">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Order</label>
                    <input type="number" class="form-control" name="options[${optionCount}][order]" 
                           value="${optionCount}" min="1" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-option" title="Remove Option">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            if (optionsContainer) {
                optionsContainer.appendChild(optionDiv);
                console.log('Option added successfully to container');
            } else {
                console.error('ERROR: Options container not found!');
            }
        }

        // Function to remove option
        function removeOption(button) {
            button.closest('.row').remove();
            console.log('Option removed');
        }

        // Add event listeners
        if (questionTypeSelect) {
            questionTypeSelect.addEventListener('change', toggleSections);
            console.log('Event listener added to question type select');
        } else {
            console.error('ERROR: Question type select not found!');
        }
        
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', addOption);
            console.log('Event listener added to add option button');
        } else {
            console.error('ERROR: Add option button not found!');
        }
        
        // Remove option event delegation
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-option') || e.target.closest('.remove-option')) {
                removeOption(e.target.closest('.remove-option'));
            }
        });

        // Initialize
        console.log('Initializing form...');
        toggleSections();
        console.log('=== INITIALIZATION COMPLETE ===');
    });

    console.log('=== SCRIPT LOADED ===');
    </script>
    @endpush
@endsection
