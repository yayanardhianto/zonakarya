<div class="row">
    <div class="col-md-6">
        <h5>{{ __('Session Information') }}</h5>
        <table class="table table-borderless">
            <tr>
                <th width="150">{{ __('Session ID') }}:</th>
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
                        @default
                            <span class="badge bg-secondary">{{ ucfirst($testSession->status) }}</span>
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>{{ __('Package') }}:</th>
                <td>{{ $testSession->package->name }}</td>
            </tr>
            <tr>
                <th>{{ __('Duration') }}:</th>
                <td>{{ $testSession->package->duration_formatted }}</td>
            </tr>
            @if($testSession->started_at)
            <tr>
                <th>{{ __('Started At') }}:</th>
                <td>{{ $testSession->started_at->format('d M Y H:i:s') }}</td>
            </tr>
            @endif
            @if($testSession->completed_at)
            <tr>
                <th>{{ __('Completed At') }}:</th>
                <td>{{ $testSession->completed_at->format('d M Y H:i:s') }}</td>
            </tr>
            @endif
            @if($testSession->score !== null)
            <tr>
                <th>{{ __('Score') }}:</th>
                <td>
                    <span class="badge {{ $testSession->is_passed ? 'bg-success' : 'bg-danger' }}">
                        {{ $testSession->score }}/100
                    </span>
                    @if($testSession->is_passed)
                        <span class="text-success">{{ __('Passed') }}</span>
                    @else
                        <span class="text-danger">{{ __('Failed') }}</span>
                    @endif
                </td>
            </tr>
            @elseif(isset($multipleChoiceScorePercentage) && $multipleChoiceScorePercentage !== null)
            <tr>
                <th>{{ __('Score (Multiple Choice Only)') }}:</th>
                <td>
                    <span class="badge bg-info">
                        {{ $multipleChoiceScorePercentage }}%
                    </span>
                    <small class="text-muted ms-2">
                        ({{ $multipleChoiceScore ?? 0 }}/{{ $multipleChoiceMax ?? 0 }} points)
                    </small>
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            {{ __('Score calculated from multiple choice questions only. Other question types require manual review.') }}
                        </small>
                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="col-md-6">
        <h5>{{ __('Applicant Information') }}</h5>
        <table class="table table-borderless">
            <tr>
                <th width="150">{{ __('Name') }}:</th>
                <td>{{ $testSession->applicant->name }}</td>
            </tr>
            <tr>
                <th>{{ __('Email') }}:</th>
                <td>{{ $testSession->applicant->email }}</td>
            </tr>
            <tr>
                <th>{{ __('WhatsApp') }}:</th>
                <td>{{ $testSession->applicant->whatsapp }}</td>
            </tr>
            <tr>
                <th>{{ __('Status') }}:</th>
                <td>
                    <span class="badge badge-{{ $testSession->applicant->status_badge }}">
                        {{ $testSession->applicant->status_text }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

@if($testSession->answers->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <h5>{{ __('Test Answers') }}</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Question') }}</th>
                        <th>{{ __('Answer') }}</th>
                        <th>{{ __('Correct') }}</th>
                        <th>{{ __('Points') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testSession->answers as $answer)
                    <tr>
                        <td>
                            <strong>{{ $answer->question->question }}</strong>
                            @if($answer->question->isMultipleChoice())
                                <br><small class="text-muted">{{ __('Multiple Choice') }}</small>
                            @elseif($answer->question->isEssay())
                                <br><small class="text-muted">{{ __('Essay') }}</small>
                            @elseif($answer->question->isScale())
                                <br><small class="text-muted">{{ __('Scale') }}</small>
                            @elseif($answer->question->isVideoRecord())
                                <br><small class="text-muted">{{ __('Video Record') }}</small>
                            @endif
                        </td>
                        <td>
                            @if($answer->question->isMultipleChoice() && $answer->selectedOption)
                                {{ $answer->selectedOption->option_text }}
                            @elseif($answer->question->isEssay())
                                <div class="text-break">{{ $answer->answer_text }}</div>
                            @elseif($answer->question->isScale())
                                {{ $answer->scale_value }}/10
                            @elseif($answer->question->isVideoRecord())
                                @if($answer->video_answer)
                                    <div class="video-answer-container">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-video text-success me-2"></i>
                                            <strong>{{ __('Video Recorded') }}</strong>
                                        </div>
                                        <video controls class="w-100" style="max-height: 200px; border-radius: 8px;">
                                            <source src="{{ $answer->video_answer }}" type="video/webm">
                                            <source src="{{ $answer->video_answer }}" type="video/mp4">
                                            {{ __('Your browser does not support the video tag.') }}
                                        </video>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                {{ __('Video recorded during test session') }}
                                            </small>
                                        </div>
                                    </div>
                                @elseif($answer->answer_text)
                                    <div class="video-answer-container">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-file-text text-info me-2"></i>
                                            <strong>{{ __('Text Testimonial') }}</strong>
                                        </div>
                                        <div class="text-break">{{ $answer->answer_text }}</div>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                {{ __('Text testimonial provided (camera not available)') }}
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">{{ __('No answer provided') }}</span>
                                @endif
                            @else
                                <span class="text-muted">{{ __('No answer') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($answer->question->isMultipleChoice())
                                @if($answer->is_correct)
                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $answer->points_earned ?? 0 }}/{{ $answer->question->points }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($testSession->notes)
<div class="row mt-4">
    <div class="col-12">
        <h5>{{ __('Notes') }}</h5>
        <div class="alert alert-info">
            {{ $testSession->notes }}
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12 text-center">
        <a href="{{ route('admin.test-session.show', $testSession) }}" 
           class="btn btn-primary" 
           target="_blank"
           title="{{ __('View Full Test Details') }}">
            <i class="fas fa-external-link-alt"></i> {{ __('View Full Test Details') }}
        </a>
    </div>
</div>

<style>
.video-answer-container {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
}

.video-answer-container video {
    background: #000;
    border-radius: 8px;
}
</style>
