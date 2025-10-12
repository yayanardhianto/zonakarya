@extends('frontend.layouts.master')
@section('title', __('Test Result'))

@section('contents')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="mb-4">
                        @if($session->score === null)
                            {{-- Test contains essay or scale questions - no score --}}
                            <div class="text-primary">
                                <i class="fas fa-clipboard-check fa-5x"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-primary mt-3">{{ __('Test Completed!') }}</h1>
                            <p class="lead text-primary">{{ __('Thank you for completing the test. Your answers have been submitted for review.') }}</p>
                        @elseif($session->is_passed)
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-5x"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-success mt-3">{{ __('Congratulations!') }}</h1>
                            <p class="lead text-success">{{ __('You have passed the test!') }}</p>
                        @else
                            <div class="text-danger">
                                <i class="fas fa-times-circle fa-5x"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-danger mt-3">{{ __('Test Not Passed') }}</h1>
                            <p class="lead text-danger">{{ __('You did not meet the passing score.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Test Summary Card -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">{{ __('Test Summary') }}</h4>
                        
                        <div class="row text-center">
                            @if($session->score === null)
                                {{-- No score for mixed question types --}}
                                <div class="col-md-4 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1">{{ $session->package->total_questions }}</h5>
                                        <p class="text-muted mb-0">{{ __('Total Questions') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1">{{ $session->answers->count() }}</h5>
                                        <p class="text-muted mb-0">{{ __('Questions Answered') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h5 class="text-info mb-1">{{ __('Under Review') }}</h5>
                                    <p class="text-muted mb-0">{{ __('Status') }}</p>
                                </div>
                            @elseif($session->package->show_score_to_user)
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">{{ $session->score }}%</h5>
                                        <p class="text-muted mb-0">{{ __('Your Score') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-info mb-1">{{ $session->package->passing_score }}%</h5>
                                        <p class="text-muted mb-0">{{ __('Passing Score') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1">{{ $session->package->total_questions }}</h5>
                                        <p class="text-muted mb-0">{{ __('Total Questions') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h5 class="text-success mb-1">{{ $session->answers->count() }}</h5>
                                    <p class="text-muted mb-0">{{ __('Questions Answered') }}</p>
                                </div>
                            @else
                                <div class="col-md-4 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-info mb-1">{{ $session->package->passing_score }}%</h5>
                                        <p class="text-muted mb-0">{{ __('Passing Score') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1">{{ $session->package->total_questions }}</h5>
                                        <p class="text-muted mb-0">{{ __('Total Questions') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                <h5 class="text-success mb-1">{{ $session->answers->count() }}</h5>
                                <p class="text-muted mb-0">{{ __('Questions Answered') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Test Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Test Details') }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">{{ __('Test Name') }}:</th>
                                        <td>{{ $session->package->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Category') }}:</th>
                                        <td>{{ $session->package->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Duration') }}:</th>
                                        <td>{{ $session->package->getDurationFormattedWithQuestionTime() }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">{{ __('Started At') }}:</th>
                                        <td>{{ $session->started_at ? $session->started_at->format('d M Y H:i:s') : __('N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Completed At') }}:</th>
                                        <td>{{ $session->completed_at ? $session->completed_at->format('d M Y H:i:s') : __('N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Time Taken') }}:</th>
                                        <td>
                                            @if($session->started_at && $session->completed_at)
                                                {{ $session->started_at->diffForHumans($session->completed_at, true) }}
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Answer Review -->
                @if($session->answers->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Answer Review') }}</h5>
                            @if($session->score === null)
                                <p class="text-muted">{{ __('Review your answers. Multiple choice questions show correct/incorrect status.') }}</p>
                            @else
                            <p class="text-muted">{{ __('Review your answers and see the correct solutions.') }}</p>
                            @endif
                            
                            <div class="accordion" id="answersAccordion">
                                @foreach($session->answers as $index => $answer)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                                                    type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse{{ $index }}">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="text-start">
                                                            <strong>{{ __('Question') }} {{ $questionNumbers[$answer->question->id] ?? $answer->question->order }}:</strong>
                                                        @if($answer->question->isForcedChoice())
                                                            {{ Str::limit(strip_tags($answer->question->getForcedChoiceInstruction()), 60) }}
                                                        @else
                                                            {{ Str::limit(strip_tags($answer->question->question_text), 60) }}
                                                        @endif
                                                    </div>
                                                            @if($answer->question->question_type === 'multiple_choice')
                                                        @if($answer->is_correct !== null)
                                                            @if($answer->is_correct)
                                                                <span class="badge bg-success">{{ __('Correct') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Incorrect') }}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-warning">{{ __('Pending Review') }}</span>
                                                                @endif
                                                            @elseif($answer->question->question_type === 'essay')
                                                                <span class="badge bg-info">{{ __('Essay') }}</span>
                                                            @elseif($answer->question->question_type === 'scale')
                                                                <span class="badge bg-primary">{{ __('Scale') }}</span>
                                                            @elseif($answer->question->question_type === 'video_record')
                                                                <span class="badge bg-success">{{ __('Video Record') }}</span>
                                                            @elseif($answer->question->question_type === 'forced_choice')
                                                                <span class="badge bg-warning">{{ __('Forced Choice') }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ __('Other') }}</span>
                                                        @endif
                                                    </div>
                                                    <!-- <div>
                                                        @if($answer->question->question_type === 'multiple_choice')
                                                            @if($answer->is_correct !== null)
                                                                @if($answer->is_correct)
                                                                    <span class="badge bg-success">{{ __('Correct') }}</span>
                                                                @else
                                                                    <span class="badge bg-danger">{{ __('Incorrect') }}</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-warning">{{ __('Pending Review') }}</span>
                                                            @endif
                                                        @elseif($answer->question->question_type === 'essay')
                                                            <span class="badge bg-info">{{ __('Essay') }}</span>
                                                        @elseif($answer->question->question_type === 'scale')
                                                            <span class="badge bg-primary">{{ __('Scale') }}</span>
                                                        @elseif($answer->question->question_type === 'video_record')
                                                            <span class="badge bg-success">{{ __('Video Record') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('Other') }}</span>
                                                        @endif
                                                    </div> -->
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" 
                                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                             data-bs-parent="#answersAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6>{{ __('Question') }}:</h6>
                                                        <p class="mb-3">
                                                            @if($answer->question->isForcedChoice())
                                                                {!! nl2br(e($answer->question->getForcedChoiceInstruction())) !!}
                                                            @else
                                                                {!! nl2br(e($answer->question->question_text)) !!}
                                                            @endif
                                                        </p>
                                                        
                                                        @if($answer->question->isMultipleChoice())
                                                            <h6>{{ __('Your Answer') }}:</h6>
                                                            @if($answer->selectedOption)
                                                                <div class="alert {{ $answer->is_correct ? 'alert-success' : 'alert-danger' }}">
                                                                    <strong>{{ $answer->selectedOption->option_text }}</strong>
                                                                    @if($answer->is_correct)
                                                                        <i class="fas fa-check text-success ms-2"></i>
                                                                    @else
                                                                        <i class="fas fa-times text-danger ms-2"></i>
                                                                    @endif
                                                                </div>
                                                                
                                                                @if(!$answer->is_correct)
                                                                    <h6>{{ __('Correct Answer') }}:</h6>
                                                                    <div class="alert alert-success">
                                                                        <strong>{{ $answer->question->options->where('is_correct', true)->first()->option_text ?? __('Not found') }}</strong>
                                                                        <i class="fas fa-check text-success ms-2"></i>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div class="alert alert-warning">{{ __('No answer selected') }}</div>
                                                            @endif
                                                        @elseif($answer->question->isScale())
                                                            <h6>{{ __('Your Rating') }}:</h6>
                                                            <div class="alert alert-info">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="scale-display-result me-3">
                                                                        <span class="scale-value fs-2 fw-bold text-primary">{{ $answer->scale_value ?? 0 }}</span>
                                                                        <div class="scale-description">
                                                                            <small class="text-muted">{{ __('out of 10') }}</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="scale-bar">
                                                                        <div class="progress" style="height: 20px; width: 200px;">
                                                                            <div class="progress-bar" 
                                                                                 style="width: {{ ($answer->scale_value ?? 0) * 10 }}%; 
                                                                                        background: linear-gradient(to right, #dc3545 0%, #ffc107 50%, #28a745 100%);">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($answer->question->isEssay())
                                                            <h6>{{ __('Your Answer') }}:</h6>
                                                            <div class="alert alert-info">
                                                                {!! nl2br(e($answer->answer_text)) !!}
                                                            </div>
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-clock"></i> {{ __('This essay question is pending manual review by administrators.') }}
                                                            </div>
                                                        @elseif($answer->question->isVideoRecord())
                                                            <h6>{{ __('Your Video Answer') }}:</h6>
                                                            @if($answer->video_answer)
                                                                <div class="alert alert-success">
                                                                    <div class="d-flex align-items-center mb-3">
                                                                        <i class="fas fa-video text-success me-2"></i>
                                                                        <strong>{{ __('Video Recorded') }}</strong>
                                                                    </div>
                                                                    <video controls class="w-100" style="max-height: 400px; border-radius: 8px;">
                                                                        <source src="{{ $answer->video_answer }}" type="video/webm">
                                                                        <source src="{{ $answer->video_answer }}" type="video/mp4">
                                                                        {{ __('Your browser does not support the video tag.') }}
                                                                    </video>
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-info-circle"></i>
                                                                            {{ __('Video recorded during test session') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            @elseif($answer->answer_text)
                                                                <div class="alert alert-info">
                                                                    <div class="d-flex align-items-center mb-3">
                                                                        <i class="fas fa-file-text text-info me-2"></i>
                                                                        <strong>{{ __('Text Testimonial') }}</strong>
                                                                    </div>
                                                                    <div class="text-break">{{ $answer->answer_text }}</div>
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-info-circle"></i>
                                                                            {{ __('Text testimonial provided (camera not available)') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle"></i>
                                                                    {{ __('No answer was provided for this question.') }}
                                                                </div>
                                                            @endif
                                                        @elseif($answer->question->isForcedChoice())
                                                            <h6>{{ __('Your Ranking Answer') }}:</h6>
                                                            @php
                                                                $forcedChoiceData = json_decode($answer->answer_text, true);
                                                                $traits = $answer->question->getForcedChoiceTraits();
                                                                if (empty($traits)) {
                                                                    $traits = [
                                                                        'Gampangan, Mudah Setuju',
                                                                        'Percaya, Mudah Percaya Pada Orang Lain', 
                                                                        'Petualang, Mengambil Resiko',
                                                                        'Toleran, Menghormati'
                                                                    ];
                                                                }
                                                            @endphp
                                                            
                                                            @if($forcedChoiceData && isset($forcedChoiceData['most_similar']) && isset($forcedChoiceData['least_similar']))
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="alert alert-success">
                                                                            <h6 class="mb-2"><i class="fas fa-thumbs-up"></i> {{ __('MOST Similar') }}</h6>
                                                                            <p class="mb-0"><strong>{{ $traits[$forcedChoiceData['most_similar']] ?? 'Trait #' . ($forcedChoiceData['most_similar'] + 1) }}</strong></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="alert alert-warning">
                                                                            <h6 class="mb-2"><i class="fas fa-thumbs-down"></i> {{ __('LEAST Similar') }}</h6>
                                                                            <p class="mb-0"><strong>{{ $traits[$forcedChoiceData['least_similar']] ?? 'Trait #' . ($forcedChoiceData['least_similar'] + 1) }}</strong></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-secondary">
                                                                    <p class="mb-0">{{ __('No valid ranking answer recorded.') }}</p>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <h6>{{ __('Your Answer') }}:</h6>
                                                            <div class="alert alert-info">
                                                                {!! nl2br(e($answer->answer_text)) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>{{ __('Question Details') }}:</h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>{{ __('Type') }}:</strong> 
                                                                @if($answer->question->isMultipleChoice())
                                                                    {{ __('Multiple Choice') }}
                                                                @elseif($answer->question->isScale())
                                                                    {{ __('Scale (1-10)') }}
                                                                @elseif($answer->question->isVideoRecord())
                                                                    {{ __('Video Record') }}
                                                                @elseif($answer->question->isForcedChoice())
                                                                    {{ __('Forced Choice') }}
                                                                @else
                                                                    {{ __('Essay') }}
                                                                @endif
                                                            </li>
                                                            <li><strong>{{ __('Points') }}:</strong> {{ $answer->question->points }}</li>
                                                            @if($answer->question->question_type === 'multiple_choice')
                                                            <li><strong>{{ __('Points Earned') }}:</strong> {{ $answer->points_earned }}</li>
                                                            @else
                                                                <li><strong>{{ __('Status') }}:</strong> 
                                                                    @if($answer->question->question_type === 'essay')
                                                                        {{ __('Pending Review') }}
                                                                    @elseif($answer->question->question_type === 'scale')
                                                                        {{ __('Submitted') }}
                                                                    @else
                                                                        {{ __('N/A') }}
                                                                    @endif
                                                                </li>
                                                            @endif
                                                            <li><strong>{{ __('Answered At') }}:</strong> 
                                                                {{ $answer->answered_at ? $answer->answered_at->format('d M Y H:i:s') : __('N/A') }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-list"></i> {{ __('All Jobs') }}
                    </a>
                    @if($session->jobVacancy)
                        <a href="{{ route('jobs.show', parameters: $session->jobVacancy) }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-briefcase"></i> {{ __('View Job Details') }}
                        </a>
                    @endif
                </div>

                @if($session->notes)
                    <div class="alert alert-info mt-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>{{ __('Additional Notes') }}
                        </h6>
                        <p class="mb-0">{{ $session->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
    }
    
    .accordion-item {
        border-radius: 8px !important;
        margin-bottom: 10px;
    }
    
    .accordion-button {
        border-radius: 8px !important;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #e7f3ff;
        border-color: #007bff;
    }
    
    .btn {
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 500;
    }
    
    .alert {
        border-radius: 8px;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .badge {
        font-size: 0.9em;
    }
    .accordion-header .badge {
        top: 12px;
        right: 52px;
    }
    
    .scale-display-result {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        min-width: 80px;
    }
    
    .scale-bar .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .scale-bar .progress-bar {
        border-radius: 10px;
    }
</style>
@endpush
