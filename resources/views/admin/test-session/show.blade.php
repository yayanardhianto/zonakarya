@extends('admin.master_layout')
@section('title', __('Test Session Details'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Sessions Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Sessions Details') }}</div>
            </div>
        </div>
        <div class="section-body">          
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h4 class="card-title">{{ __('Test Session Details') }}</h4>
                                <a href="{{ route('admin.test-session.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Sessions') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>{{ __('Session Information') }}</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="200">{{ __('Session ID') }}:</th>
                                            <td>{{ $testSession->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}:</th>
                                            <td>
                                                @switch($testSession->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge bg-primary">{{ __('In Progress') }}</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">{{ __('Completed') }}</span>
                                                        @break
                                                    @case('expired')
                                                        <span class="badge bg-danger">{{ __('Expired') }}</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Score') }}:</th>
                                            <td>
                                                @if($testSession->score !== null)
                                                    <span class="badge {{ $testSession->is_passed ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $testSession->score }}%
                                                    </span>
                                                    @if($testSession->is_passed)
                                                        <span class="text-success ms-2">{{ __('Passed') }}</span>
                                                    @else
                                                        <span class="text-danger ms-2">{{ __('Failed') }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">{{ __('Not Available') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Started At') }}:</th>
                                            <td>
                                                @if($testSession->started_at)
                                                    {{ $testSession->started_at->format('d M Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">{{ __('Not Started') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Completed At') }}:</th>
                                            <td>
                                                @if($testSession->completed_at)
                                                    {{ $testSession->completed_at->format('d M Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">{{ __('Not Completed') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ __('Applicant/User Information') }}</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="200">{{ __('Name') }}:</th>
                                            <td>{{ $testSession->applicant ? $testSession->applicant->name : ($testSession->user ? $testSession->user->name : __('N/A')) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Email') }}:</th>
                                            <td>{{ $testSession->applicant ? $testSession->applicant->email : ($testSession->user ? $testSession->user->email : __('N/A'))  }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Job Position') }}:</th>
                                            <td>
                                                @if($testSession->jobVacancy)
                                                    {{ $testSession->jobVacancy->position }}
                                                @else
                                                    <span class="text-muted">{{ __('N/A') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>
                            <h5>{{ __('Test Package Information') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="200">{{ __('Package Name') }}:</th>
                                            <td>{{ $testSession->package->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Category') }}:</th>
                                            <td>{{ $testSession->package->category->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Duration') }}:</th>
                                            <td>{{ $testSession->package->duration_formatted }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Total Questions') }}:</th>
                                            <td>{{ $testSession->package->total_questions }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Passing Score') }}:</th>
                                            <td>{{ $testSession->package->passing_score }}%</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($testSession->answers->count() > 0)
                                <hr>
                                <h5>{{ __('Answers Review') }}</h5>
                                <div class="accordion" id="answersAccordion">
                                    @foreach($testSession->answers as $index => $answer)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                                                        type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse{{ $index }}">
                                                    <strong class="me-2">{{ __('Question') }} {{ $index + 1 }}:</strong>
                                                    @if($answer->question->isForcedChoice())
                                                        {{ Str::limit(strip_tags($answer->question->getForcedChoiceInstruction()), 80) }}
                                                    @else
                                                        {{ Str::limit(strip_tags($answer->question->question_text), 80) }}
                                                    @endif
                                                    @if($answer->is_correct !== null)
                                                        @if($answer->is_correct)
                                                            <span class="badge bg-success ms-2">{{ __('Correct') }}</span>
                                                        @else
                                                            <span class="badge bg-danger ms-2">{{ __('Incorrect') }}</span>
                                                        @endif
                                                    @endif
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $index }}" 
                                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                                data-bs-parent="#answersAccordion">
                                                <div class="accordion-body pt-4 px-4">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h6>{{ __('Question') }}:</h6>
                                                            <p>
                                                                @if($answer->question->isForcedChoice())
                                                                    {!! nl2br(e($answer->question->getForcedChoiceInstruction())) !!}
                                                                @else
                                                                    {!! nl2br(e($answer->question->question_text)) !!}
                                                                @endif
                                                            </p>
                                                            
                                                            @if($answer->question->isMultipleChoice())
                                                                <h6>{{ __('Selected Answer') }}:</h6>
                                                                @if($answer->selectedOption)
                                                                    <div class="alert {{ $answer->is_correct ? 'alert-info' : 'alert-danger' }}">
                                                                        <strong>{{ $answer->selectedOption->option_text }}</strong>
                                                                        @if($answer->is_correct)
                                                                            <i class="fas fa-check text-success ms-2"></i>
                                                                        @else
                                                                            <i class="fas fa-times text-danger ms-2"></i>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    @if(!$answer->is_correct)
                                                                        <h6>{{ __('Correct Answer') }}:</h6>
                                                                        <div class="alert alert-info">
                                                                            <strong>{{ $answer->question->options->where('is_correct', true)->first()->option_text ?? __('Not found') }}</strong>
                                                                            <i class="fas fa-check text-success ms-2"></i>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="alert alert-warning">{{ __('No answer selected') }}</div>
                                                                @endif
                                                            @elseif($answer->question->isVideoRecord())
                                                                <h6>{{ __('Video Answer') }}:</h6>
                                                                @if($answer->video_answer)
                                                                    <div class="alert alert-dark">
                                                                        <div class="d-flex align-items-center mb-3">
                                                                            <i class="fas fa-video text-white me-2"></i>
                                                                            <strong>{{ __('Video Recorded') }}</strong>
                                                                        </div>
                                                                        <video controls class="w-100" style="max-height: 400px; border-radius: 8px;">
                                                                            <source src="{{ $answer->video_answer }}" type="video/webm">
                                                                            <source src="{{ $answer->video_answer }}" type="video/mp4">
                                                                            {{ __('Your browser does not support the video tag.') }}
                                                                        </video>
                                                                        <div class="mt-2">
                                                                            <small class="text-white">
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
                                                                <h6>{{ __('Forced Choice Answer') }}:</h6>
                                                                @php
                                                                    $forcedChoiceData = json_decode($answer->answer_text, true);
                                                                    $traits = $answer->question->getForcedChoiceTraits();
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
                                                                            <div class="alert alert-danger">
                                                                                <h6 class="mb-2"><i class="fas fa-thumbs-down"></i> {{ __('LEAST Similar') }}</h6>
                                                                                <p class="mb-0"><strong>{{ $traits[$forcedChoiceData['least_similar']] ?? 'Trait #' . ($forcedChoiceData['least_similar'] + 1) }}</strong></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        {{ __('Invalid forced choice answer format.') }}
                                                                        <br><small class="text-muted">{{ $answer->answer_text }}</small>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <h6>{{ __('Answer') }}:</h6>
                                                                <div class="alert alert-info">
                                                                    {!! nl2br(e($answer->answer_text)) !!}
                                                                </div>
                                                                
                                                                @if(checkAdminHasPermission('test.session.grade'))
                                                                    <form action="{{ route('admin.test-session.grade-essay', $testSession) }}" 
                                                                        method="POST" class="mt-3">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label for="points_earned_{{ $answer->id }}" class="form-label">
                                                                                    {{ __('Points Earned') }} (Max: {{ $answer->question->points }})
                                                                                </label>
                                                                                <input type="number" 
                                                                                    class="form-control" 
                                                                                    id="points_earned_{{ $answer->id }}" 
                                                                                    name="answers[{{ $answer->id }}][points_earned]"
                                                                                    value="{{ $answer->points_earned }}" 
                                                                                    min="0" 
                                                                                    max="{{ $answer->question->points }}">
                                                                                <input type="hidden" 
                                                                                    name="answers[{{ $answer->id }}][question_id]" 
                                                                                    value="{{ $answer->question_id }}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">&nbsp;</label>
                                                                                <div>
                                                                                    <button type="submit" class="btn btn-primary">
                                                                                        <i class="fas fa-save"></i> {{ __('Save Grade') }}
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 ps-3">
                                                            <h6>{{ __('Question Details') }}:</h6>
                                                            <ul class="list-unstyled">
                                                                <li><strong>{{ __('Type') }}:</strong> 
                                                                    @if($answer->question->isMultipleChoice())
                                                                        {{ __('Multiple Choice') }}
                                                                    @elseif($answer->question->isVideoRecord())
                                                                        {{ __('Video Record') }}
                                                                    @else
                                                                        {{ __('Essay') }}
                                                                    @endif
                                                                </li>
                                                                <li><strong>{{ __('Points') }}:</strong> {{ $answer->question->points }}</li>
                                                                <li><strong>{{ __('Points Earned') }}:</strong> {{ $answer->points_earned }}</li>
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
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> {{ __('No answers found for this session.') }}
                                </div>
                            @endif

                            @if($testSession->notes)
                                <hr>
                                <h5>{{ __('Notes') }}</h5>
                                <div class="alert alert-warning">
                                    {{ $testSession->notes }}
                                </div>
                            @endif

                            <hr>
                            <h5>{{ __('Test Access') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{ __('Test URL') }}</h6>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" 
                                            value="{{ route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]) }}" 
                                            readonly id="test-url">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                                            <i class="fas fa-copy"></i> {{ __('Copy') }}
                                        </button>
                                    </div>
<!--                                     
                                    <h6>{{ __('QR Code') }}</h6>
                                    <div class="text-center">
                                        <img src="{{ route('test.qr-code', $testSession) }}" 
                                            alt="QR Code" 
                                            class="img-fluid border rounded" 
                                            style="max-width: 200px;">
                                        <br>
                                        <small class="text-muted">{{ __('Scan to access test') }}</small>
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __('Session Details') }}</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>{{ __('Access Token') }}:</strong> 
                                            <code>{{ $testSession->access_token ?: __('Not generated') }}</code>
                                        </li>
                                        <li><strong>{{ __('Expires At') }}:</strong> 
                                            {{ $testSession->expires_at ? $testSession->expires_at->format('Y-m-d H:i:s') : __('Not set') }}
                                        </li>
                                        <li><strong>{{ __('Created At') }}:</strong> 
                                            {{ $testSession->created_at->format('Y-m-d H:i:s') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    <script>
    function copyToClipboard() {
        const urlInput = document.getElementById('test-url');
        urlInput.select();
        urlInput.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> {{ __("Copied!") }}';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        } catch (err) {
            console.error('Failed to copy: ', err);
            alert('{{ __("Failed to copy URL") }}');
        }
    }
    </script>
@endsection