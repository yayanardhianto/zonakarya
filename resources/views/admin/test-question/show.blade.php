@extends('admin.master_layout')
@section('title', __('Test Question Details'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Question Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Question Details') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Test Question Details') }}</h3>
                                <div>
                                    @if(checkAdminHasPermission('test.question.edit'))
                                        <a href="{{ route('admin.test-question.edit', $testQuestion) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.test-question.index', ['package_id' => $testQuestion->package_id]) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back to Questions') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="150">{{ __('ID') }}:</th>
                                            <td>{{ $testQuestion->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Packages') }}:</th>
                                            <td>
                                                @if($testQuestion->packages->count() > 0)
                                                    @foreach($testQuestion->packages as $package)
                                                        <span class="badge bg-secondary me-1">{{ $package->name }}</span>
                                                        <small class="text-muted">({{ $package->category->name }})</small>
                                                        @if(!$loop->last)<br>@endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">{{ __('No packages') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Question Type') }}:</th>
                                            <td>
                                                @if($testQuestion->isMultipleChoice())
                                                    <span class="badge bg-primary">{{ __('Multiple Choice') }}</span>
                                                @elseif($testQuestion->isScale())
                                                    <span class="badge bg-success">{{ __('Scale (1-10)') }}</span>
                                                @elseif($testQuestion->isVideoRecord())
                                                    <span class="badge bg-info">{{ __('Video Record') }}</span>
                                                @elseif($testQuestion->isForcedChoice())
                                                    <span class="badge bg-warning">{{ __('Forced Choice') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Essay') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Points') }}:</th>
                                            <td>{{ $testQuestion->points }}</td>
                                        </tr>
                                        <tr style="display: none;">
                                            <th>{{ __('Order') }}:</th>
                                            <td>{{ $testQuestion->order }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Created At') }}:</th>
                                            <td>{{ $testQuestion->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Updated At') }}:</th>
                                            <td>{{ $testQuestion->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>
                            <h5>{{ __('Question Text') }}</h5>
                            <div class="card">
                                <div class="card-body">
                                    @if($testQuestion->question_image)
                                        <div class="text-center mb-3">
                                            <img src="{{ $testQuestion->image_url }}" alt="Question Image" 
                                                class="img-fluid" style="max-width: 100%; height: auto;">
                                        </div>
                                    @endif
                                    <div class="question-text">
                                        @if($testQuestion->isForcedChoice())
                                            {!! nl2br(e($testQuestion->getForcedChoiceInstruction())) !!}
                                        @else
                                            {!! nl2br(e($testQuestion->question_text)) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($testQuestion->isMultipleChoice() && $testQuestion->options->count() > 0)
                                <hr>
                                <h5>{{ __('Answer Options') }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No') }}</th>
                                                <th>{{ __('Option Text') }}</th>
                                                <th>{{ __('Correct') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($testQuestion->options->sortBy('order') as $index => $option)
                                                <tr class="{{ $option->is_correct ? 'table-success' : '' }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $option->option_text }}</td>
                                                    <td>
                                                        @if($option->is_correct)
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check"></i> {{ __('Correct') }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-times"></i> {{ __('Incorrect') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($testQuestion->isForcedChoice())
                                <hr>
                                <h5>{{ __('Forced Choice Traits') }}</h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __('Respondents will select one trait as "most similar" and one as "least similar" to themselves.') }}
                                </div>
                                <div class="row">
                                    @php
                                        $traits = $testQuestion->getForcedChoiceTraits();
                                    @endphp
                                    @if(!empty($traits))
                                        @foreach($traits as $index => $trait)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="card border-warning">
                                                    <div class="card-body py-2">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-warning me-2">{{ $index + 1 }}</span>
                                                            <span class="fw-bold">{{ $trait }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ __('No traits configured for this forced choice question.') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif($testQuestion->isScale())
                                <hr>
                                <h5>{{ __('Scale Question') }}</h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    {{ __('This is a scale question where respondents rate from 1 to 10. Points are calculated proportionally based on the selected value.') }}
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>{{ __('Scoring System') }}:</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>1-2:</strong> {{ __('Very Low') }} ({{ round(($testQuestion->points * 0.1), 1) }} - {{ round(($testQuestion->points * 0.2), 1) }} {{ __('points') }})</li>
                                            <li><strong>3-4:</strong> {{ __('Low') }} ({{ round(($testQuestion->points * 0.3), 1) }} - {{ round(($testQuestion->points * 0.4), 1) }} {{ __('points') }})</li>
                                            <li><strong>5-6:</strong> {{ __('Moderate') }} ({{ round(($testQuestion->points * 0.5), 1) }} - {{ round(($testQuestion->points * 0.6), 1) }} {{ __('points') }})</li>
                                            <li><strong>7-8:</strong> {{ __('High') }} ({{ round(($testQuestion->points * 0.7), 1) }} - {{ round(($testQuestion->points * 0.8), 1) }} {{ __('points') }})</li>
                                            <li><strong>9-10:</strong> {{ __('Very High') }} ({{ round(($testQuestion->points * 0.9), 1) }} - {{ $testQuestion->points }} {{ __('points') }})</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>{{ __('Example') }}:</h6>
                                        <div class="scale-preview">
                                            <div class="scale-slider-preview">
                                                <input type="range" class="form-range" min="1" max="10" value="5" disabled>
                                                <div class="scale-numbers d-flex justify-content-between mt-1">
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <small class="text-muted">{{ $i }}</small>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($testQuestion->isEssay())
                                <hr>
                                <h5>{{ __('Essay Question') }}</h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    {{ __('This is an essay question that requires manual grading by administrators.') }}
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

@push('styles')
<style>
    .scale-slider-preview input[type="range"] {
        height: 8px;
        border-radius: 5px;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
        background: linear-gradient(to right, #dc3545 0%, #ffc107 50%, #28a745 100%);
    }
    
    .scale-slider-preview input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .scale-numbers {
        font-size: 0.8rem;
    }
    
    .scale-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }
</style>
@endpush
