@extends('admin.master_layout')
@section('title', __('Test Package Details'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Package Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Package Details') }}</div>
            </div>
        </div>
        <div class="section-body">            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title"></h3>
                                <div>
                                    @if(checkAdminHasPermission('test.package.edit'))
                                        <a href="{{ route('admin.test-package.edit', $testPackage) }}" class="btn btn-warning me-1">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                    @endif
                                    @if(checkAdminHasPermission('test.question.view'))
                                        <a href="{{ route('admin.test-question.index', ['package_id' => $testPackage->id]) }}" class="btn btn-success me-1">
                                            <i class="fas fa-question-circle"></i> {{ __('Manage Questions') }}
                                        </a>
                                    @endif
                                    <!-- @if(checkAdminHasPermission('test.package.edit'))
                                        <a href="{{ route('admin.test-package.add-question', $testPackage) }}" class="btn btn-success">
                                            <i class="fas fa-plus-circle"></i> {{ __('Add Existing Question') }}
                                        </a>
                                    @endif -->
                                    <a href="{{ route('admin.test-package.index') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back to Packages') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-borderless detail-table">
                                        <tr>
                                            <th width="180">{{ __('ID') }}:</th>
                                            <td>{{ $testPackage->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Package Name') }}:</th>
                                            <td>{{ $testPackage->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Category') }}:</th>
                                            <td>
                                                <span class="badge bg-secondary">{{ $testPackage->category->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Description') }}:</th>
                                            <td>{{ $testPackage->description ?: __('No description') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Duration') }}:</th>
                                            <td>{{ $testPackage->getDurationFormattedWithQuestionTime() }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Total Questions') }}:</th>
                                            <td>{{ $testPackage->total_questions }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Passing Score') }}:</th>
                                            <td>{{ $testPackage->passing_score }}%</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Applicant Flow') }}:</th>
                                            <td>
                                                @if($testPackage->is_applicant_flow)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-primary">{{ __('Applicant Flow') }}</span>
                                                        @if($testPackage->is_screening_test)
                                                            <span class="badge bg-warning">{{ __('Screening Test') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">Order: {{ $testPackage->applicant_flow_order }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ __('General Test') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}:</th>
                                            <td>
                                                @if($testPackage->is_active)
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Created At') }}:</th>
                                            <td>{{ $testPackage->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Updated At') }}:</th>
                                            <td>{{ $testPackage->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($testPackage->questions->count() > 0)
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>{{ __('Questions in this Package') }}</h5>
                                    <div>
                                        @if($testPackage->randomize_questions)
                                            <span class="badge bg-info me-2">{{ __('Randomized Order') }}</span>
                                            <button class="btn btn-sm btn-outline-warning me-2" onclick="disableRandomOrder()">
                                                <i class="fas fa-sort"></i> {{ __('Disable Random') }}
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="enableDragDrop()">
                                                <i class="fas fa-sort"></i> {{ __('Reorder Questions') }}
                                            </button>
                                            <button class="btn btn-sm btn-outline-info me-2" onclick="enableRandomOrder()">
                                                <i class="fas fa-random"></i> {{ __('Random Order') }}
                                            </button>
                                        @endif
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="enable-time-per-question" 
                                                   {{ $testPackage->enable_time_per_question ? 'checked' : '' }}
                                                   onchange="toggleTimePerQuestion()">
                                            <label class="form-check-label" for="enable-time-per-question">
                                                {{ __('Enable Time Per Question') }}
                                            </label>
                                        </div>
                                        
                                        <script>
                                        // Initialize toggle state on page load
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const enableToggle = document.getElementById('enable-time-per-question');
                                            const timeColumn = document.getElementById('time-column');
                                            const timeInputs = document.querySelectorAll('.time-input');
                                            const timePanel = document.getElementById('time-settings-panel');
                                            
                                            console.log('Inline script: Initializing toggle state...');
                                            console.log('Toggle checked:', enableToggle ? enableToggle.checked : 'Toggle not found');
                                            
                                            if (enableToggle && enableToggle.checked) {
                                                console.log('Inline script: Toggle is enabled, showing time elements...');
                                                if (timeColumn) timeColumn.style.display = 'table-cell';
                                                timeInputs.forEach(input => input.style.display = 'table-cell');
                                                if (timePanel) timePanel.style.display = 'block';
                                            } else {
                                                console.log('Inline script: Toggle is disabled, hiding time elements...');
                                                if (timeColumn) timeColumn.style.display = 'none';
                                                timeInputs.forEach(input => input.style.display = 'none');
                                                if (timePanel) timePanel.style.display = 'none';
                                            }
                                        });
                                        
                                        function toggleTimePerQuestion() {
                                            const checkbox = document.getElementById('enable-time-per-question');
                                            const enable = checkbox.checked;
                                            
                                            fetch('{{ route("admin.test-package.toggle-time-per-question", $testPackage) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify({
                                                    enable_time_per_question: enable
                                                })
                                            })
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error(`HTTP error! status: ${response.status}`);
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                if (data.success) {
                                                    // Show/hide time column and inputs
                                                    const timeColumn = document.getElementById('time-column');
                                                    const timeInputs = document.querySelectorAll('.time-input');
                                                    const timePanel = document.getElementById('time-settings-panel');
                                                    
                                                    if (enable) {
                                                        if (timeColumn) timeColumn.style.display = 'table-cell';
                                                        timeInputs.forEach(input => input.style.display = 'table-cell');
                                                        if (timePanel) timePanel.style.display = 'block';
                                                    } else {
                                                        if (timeColumn) timeColumn.style.display = 'none';
                                                        timeInputs.forEach(input => input.style.display = 'none');
                                                        if (timePanel) timePanel.style.display = 'none';
                                                    }
                                                    
                                                    // Update duration display
                                                    if (data.formatted_duration) {
                                                        document.getElementById('duration-display').textContent = data.formatted_duration;
                                                    }
                                                    
                                                    showAlert('success', data.message);
                                                } else {
                                                    // Revert checkbox state
                                                    checkbox.checked = !enable;
                                                    showAlert('error', data.message || 'Failed to toggle time per question');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error toggling time per question:', error);
                                                // Revert checkbox state
                                                checkbox.checked = !enable;
                                                showAlert('error', 'Failed to toggle time per question: ' + error.message);
                                            });
                                        }
                                        </script>
                                    </div>
                                </div>
                                
                                <!-- Package Status Info -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info mb-0">
                                            <strong>{{ __('Total Duration') }}:</strong> 
                                            <span id="duration-display">{{ $testPackage->getDurationFormattedWithQuestionTime() }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert {{ $testPackage->randomize_questions ? 'alert-warning' : 'alert-success' }} mb-0">
                                            <strong>{{ __('Question Order') }}:</strong> 
                                            @if($testPackage->randomize_questions)
                                                <i class="fas fa-random"></i> {{ __('Randomized') }}
                                                <small class="d-block text-white">{{ __('Questions will appear in different order for each test taker') }}</small>
                                            @else
                                                <i class="fas fa-sort"></i> {{ __('Custom Order') }}
                                                <small class="d-block text-white">{{ __('Questions will appear in the order you set') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="questions-table">
                                        <thead>
                                            <tr>
                                                <th width="50">{{ __('Order') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Points') }}</th>
                                                <th>{{ __('Options') }}</th>
                                                @if($testPackage->randomize_questions)
                                                    <th width="80" class="text-center">{{ __('First') }}</th>
                                                    <th width="80" class="text-center">{{ __('Last') }}</th>
                                                @endif
                                                <th width="150" id="time-column" style="display: none;">{{ __('Time (seconds)') }}</th>
                                                <th width="120">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sortable-questions">
                                            @foreach($testPackage->getOrderedQuestions() as $index => $question)
                                                <tr data-question-id="{{ $question->id }}">
                                                    <td class="drag-handle" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical"></i>
                                                        <span class="order-number">{{ $index + 1 }}</span>
                                                    </td>
                                                    <td>
                                                        @if($question->isForcedChoice())
                                                            @php
                                                                $traits = $question->getForcedChoiceTraits();
                                                                $displayText = !empty($traits) ? implode(', ', array_slice($traits, 0, 2)) . (count($traits) > 2 ? '...' : '') : 'Forced Choice Question';
                                                            @endphp
                                                            {{ $displayText }}
                                                        @else
                                                            {{ Str::limit(strip_tags($question->question_text), 50) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($question->isMultipleChoice())
                                                            <span class="badge bg-primary">{{ __('Multiple Choice') }}</span>
                                                        @elseif($question->isScale())
                                                            <span class="badge bg-info">{{ __('Scale') }}</span>
                                                        @elseif($question->isVideoRecord())
                                                            <span class="badge bg-success">{{ __('Video Record') }}</span>
                                                        @elseif($question->isForcedChoice())
                                                            <span class="badge bg-warning">{{ __('Forced Choice') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('Essay') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $question->points }}</td>
                                                    <td>{{ $question->options->count() }}</td>
                                                    @if($testPackage->randomize_questions)
                                                        <td class="text-center">
                                                            <div class="form-check d-inline-block">
                                                                <input class="form-check-input fixed-question-checkbox first" 
                                                                       type="checkbox" 
                                                                       id="first-{{ $question->id }}"
                                                                       data-question-id="{{ $question->id }}"
                                                                       data-type="first"
                                                                       {{ $testPackage->fixed_first_question_id == $question->id ? 'checked' : '' }}
                                                                       onchange="updateFixedQuestion('first', {{ $question->id }}, this.checked)">
                                                                <!-- <label class="form-check-label" for="first-{{ $question->id }}">
                                                                    <small>{{ __('First') }}</small>
                                                                </label> -->
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check d-inline-block">
                                                                <input class="form-check-input fixed-question-checkbox last" 
                                                                       type="checkbox" 
                                                                       id="last-{{ $question->id }}"
                                                                       data-question-id="{{ $question->id }}"
                                                                       data-type="last"
                                                                       {{ $testPackage->fixed_last_question_id == $question->id ? 'checked' : '' }}
                                                                       onchange="updateFixedQuestion('last', {{ $question->id }}, this.checked)">
                                                                <!-- <label class="form-check-label" for="last-{{ $question->id }}">
                                                                    <small>{{ __('Last') }}</small>
                                                                </label> -->
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td class="time-input" style="display: none;">
                                                        <input type="number" 
                                                               class="form-control form-control-sm question-time-input" 
                                                               data-question-id="{{ $question->id }}"
                                                               value="{{ $testPackage->getQuestionTime($question->id) ?? '' }}"
                                                               min="1" max="3600" 
                                                               placeholder="Auto">
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            @if(checkAdminHasPermission('test.question.view'))
                                                                <a href="{{ route('admin.test-question.show', $question) }}" 
                                                                class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkAdminHasPermission('test.package.edit'))
                                                                <form action="{{ route('admin.test-package.detach-question', [$testPackage, $question]) }}" 
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('{{ __('Are you sure you want to remove this question from the package?') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Time Settings Panel -->
                                <div id="time-settings-panel" class="card mt-3" style="display: none;">
                                    <div class="card-header">
                                        <h6>{{ __('Time Per Question Settings') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Set Time for All Questions') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" id="bulk-time-input" class="form-control" min="1" max="3600" placeholder="Seconds">
                                                        <button class="btn btn-outline-primary" onclick="setBulkTime()">
                                                            {{ __('Apply to All') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('Actions') }}</label>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-success" onclick="saveTimeSettings()">
                                                            <i class="fas fa-save"></i> {{ __('Save Changes') }}
                                                        </button>
                                                        <button class="btn btn-light" onclick="clearAllTimes()">
                                                            <i class="fas fa-times"></i> {{ __('Clear All') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> {{ __('No questions found in this package.') }}
                                    @if(checkAdminHasPermission('test.question.create'))
                                        <a href="{{ route('admin.test-question.create', ['package_id' => $testPackage->id]) }}" class="btn btn-sm btn-primary ms-2">
                                            <i class="fas fa-plus"></i> {{ __('Add Questions') }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let sortable = null;
let isDragMode = false;

function enableDragDrop() {
    if (sortable) {
        sortable.destroy();
    }
    
    const tbody = document.getElementById('sortable-questions');
    sortable = Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            updateQuestionOrder();
        }
    });
    
    isDragMode = true;
    document.querySelectorAll('.drag-handle').forEach(handle => {
        handle.style.display = 'block';
    });
    
    // Show save button
    const saveBtn = document.createElement('button');
    saveBtn.className = 'btn btn-success btn-sm ms-2';
    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Order';
    saveBtn.onclick = saveQuestionOrder;
    
    const reorderBtn = document.querySelector('button[onclick="enableDragDrop()"]');
    reorderBtn.parentNode.insertBefore(saveBtn, reorderBtn.nextSibling);
    reorderBtn.style.display = 'none';
}

function updateQuestionOrder() {
    const rows = document.querySelectorAll('#sortable-questions tr');
    rows.forEach((row, index) => {
        const orderSpan = row.querySelector('.order-number');
        if (orderSpan) {
            orderSpan.textContent = index + 1;
        }
    });
}

function saveQuestionOrder() {
    const rows = document.querySelectorAll('#sortable-questions tr');
    const questionOrder = Array.from(rows).map(row => row.dataset.questionId);
    
    fetch('{{ route("admin.test-package.update-question-order", $testPackage) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_order: questionOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message || 'Failed to update question order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating question order');
    });
}

function enableRandomOrder() {
    if (confirm('{{ __("Are you sure you want to enable random question order? This will disable custom ordering.") }}')) {
        fetch('{{ route("admin.test-package.randomize-questions", $testPackage) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message || 'Failed to enable random order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while enabling random order');
        });
    }
}

function disableRandomOrder() {
    if (confirm('{{ __("Are you sure you want to disable random question order? You can then set custom order.") }}')) {
        fetch('{{ route("admin.test-package.set-custom-order", $testPackage) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message || 'Failed to disable random order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while disabling random order');
        });
    }
}

function updateFixedQuestion(type, questionId, isChecked) {
    const fieldName = type === 'first' ? 'fix_first_question' : 'fix_last_question';
    const questionFieldName = type === 'first' ? 'fixed_first_question_id' : 'fixed_last_question_id';
    
    // If unchecking, clear all checkboxes of the same type
    if (!isChecked) {
        document.querySelectorAll(`input[data-type="${type}"]`).forEach(checkbox => {
            checkbox.checked = false;
        });
    } else {
        // If checking, uncheck all other checkboxes of the same type
        document.querySelectorAll(`input[data-type="${type}"]`).forEach(checkbox => {
            if (checkbox.dataset.questionId != questionId) {
                checkbox.checked = false;
            }
        });
    }
    
    // Determine if any checkbox of this type is checked
    const anyChecked = document.querySelector(`input[data-type="${type}"]:checked`) !== null;
    const selectedQuestionId = anyChecked ? questionId : null;
    
    fetch('{{ route("admin.test-package.update-fixed-question", $testPackage) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            [fieldName]: anyChecked,
            [questionFieldName]: selectedQuestionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('error', data.message || 'Failed to update fixed question');
            // Revert checkbox state on error
            document.getElementById(`${type}-${questionId}`).checked = !isChecked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating fixed question');
        // Revert checkbox state on error
        document.getElementById(`${type}-${questionId}`).checked = !isChecked;
    });
}

// Functions moved to global scope for onclick handlers

// Auto-save individual question time changes
document.addEventListener('DOMContentLoaded', function() {
    // Initialize toggle state based on database value
    const enableToggle = document.getElementById('enable-time-per-question');
    const timeColumn = document.getElementById('time-column');
    const timeInputs = document.querySelectorAll('.time-input');
    const timePanel = document.getElementById('time-settings-panel');
    
    console.log('Initializing toggle state...');
    console.log('Toggle checked:', enableToggle ? enableToggle.checked : 'Toggle not found');
    console.log('Time column found:', !!timeColumn);
    console.log('Time inputs found:', timeInputs.length);
    console.log('Time panel found:', !!timePanel);
    
    if (enableToggle && enableToggle.checked) {
        console.log('Toggle is enabled, showing time elements...');
        // Show time column and inputs if toggle is enabled
        if (timeColumn) timeColumn.style.display = 'table-cell';
        timeInputs.forEach(input => input.style.display = 'table-cell');
        if (timePanel) timePanel.style.display = 'block';
    } else {
        console.log('Toggle is disabled, hiding time elements...');
        // Hide time column and inputs if toggle is disabled
        if (timeColumn) timeColumn.style.display = 'none';
        timeInputs.forEach(input => input.style.display = 'none');
        if (timePanel) timePanel.style.display = 'none';
    }
    
    document.querySelectorAll('.question-time-input').forEach(input => {
        input.addEventListener('blur', function() {
            const questionId = this.dataset.questionId;
            const timeValue = this.value;
            
            if (timeValue && timeValue > 0) {
                // Show saving indicator
                const originalValue = this.value;
                this.disabled = true;
                this.placeholder = 'Saving...';
                
                fetch('{{ route("admin.test-package.update-question-time", $testPackage) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        time_per_question_seconds: parseInt(timeValue)
                    })
                })
                .then(response => {
                    console.log('Auto-save response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Auto-save response data:', data);
                    if (data.success) {
                        document.getElementById('duration-display').textContent = data.formatted_duration;
                        showAlert('success', 'Question time saved automatically');
                        // Keep the current value, don't revert
                        this.value = originalValue;
                    } else {
                        this.value = originalValue;
                        showAlert('error', 'Failed to save question time: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Auto-save error:', error);
                    // Try bulk save as fallback
                    console.log('Trying bulk save as fallback...');
                    saveTimeSettings();
                    this.value = originalValue;
                })
                .finally(() => {
                    this.disabled = false;
                    this.placeholder = 'Auto';
                });
            }
        });
    });
});


function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                     type === 'error' ? 'alert-danger' : 
                     type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.card-body');
    container.insertBefore(alert, container.firstChild);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

function saveTimeSettings() {
    const questionTimes = {};
    document.querySelectorAll('.question-time-input').forEach(input => {
        const questionId = input.dataset.questionId;
        const timeValue = input.value;
        questionTimes[questionId] = timeValue ? parseInt(timeValue) : null;
    });
    
    console.log('Saving question times:', questionTimes);
    
    // Check if any time is set
    const hasAnyTime = Object.values(questionTimes).some(time => time !== null);
    if (!hasAnyTime) {
        showAlert('warning', 'Please set at least one question time before saving');
        return;
    }
    
    fetch('{{ route("admin.test-package.bulk-update-question-times", $testPackage) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_times: questionTimes
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            });
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            document.getElementById('duration-display').textContent = data.formatted_duration;
        } else {
            showAlert('error', data.message || 'Failed to update question times');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating question times: ' + error.message);
    });
}

function setBulkTime() {
    const bulkTime = document.getElementById('bulk-time-input').value;
    if (!bulkTime || bulkTime < 1) {
        showAlert('warning', 'Please enter a valid time');
        return;
    }
    
    document.querySelectorAll('.question-time-input').forEach(input => {
        input.value = bulkTime;
    });
    
    showAlert('info', 'Time applied to all questions');
}

function clearAllTimes() {
    document.querySelectorAll('.question-time-input').forEach(input => {
        input.value = '';
    });
    showAlert('info', 'All question times cleared');
}


function saveTimeSettings() {
    const timeInputs = document.querySelectorAll('.question-time-input');
    const questionTimes = {};
    
    timeInputs.forEach(input => {
        const questionId = input.dataset.questionId;
        const timeValue = input.value;
        
        if (timeValue && timeValue > 0) {
            questionTimes[questionId] = parseInt(timeValue);
        }
    });
    
    if (Object.keys(questionTimes).length === 0) {
        showAlert('error', 'Please set at least one question time before saving');
        return;
    }
    
    fetch('{{ route("admin.test-package.bulk-update-question-times", $testPackage) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_times: questionTimes
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('duration-display').textContent = data.formatted_duration;
            showAlert('success', data.message);
        } else {
            showAlert('error', data.message || 'Failed to save question times');
        }
    })
    .catch(error => {
        console.error('Error saving question times:', error);
        showAlert('error', 'Failed to save question times: ' + error.message);
    });
}

function clearAllTimes() {
    document.querySelectorAll('.question-time-input').forEach(input => {
        input.value = '';
    });
    showAlert('info', 'All question times cleared');
}
</script>
@endpush

@push('css')
<style>
.drag-handle {
    cursor: move;
    user-select: none;
}

.drag-handle:hover {
    background-color: #f8f9fa;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #e3f2fd;
}

.question-time-input {
    width: 80px;
}

.question-time-input:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
    cursor: not-allowed;
}

.question-time-input:disabled::placeholder {
    color: #6c757d;
    font-style: italic;
}

#time-settings-panel {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.gap-2 {
    gap: 0.5rem;
}

.detail-table th {
    height: 40px !important;
}

.fixed-question-checkbox {
    transform: scale(1.2);
}

.fixed-question-checkbox:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check-label small {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>
@endpush
