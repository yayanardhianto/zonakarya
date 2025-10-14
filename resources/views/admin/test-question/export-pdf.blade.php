<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header .subtitle {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .question-item {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            page-break-inside: avoid;
        }
        
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .question-number {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }
        
        .question-meta {
            font-size: 11px;
            color: #666;
        }
        
        .question-text {
            font-size: 13px;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .question-options {
            margin-left: 20px;
        }
        
        .option-item {
            margin-bottom: 8px;
            padding: 5px;
            border-left: 3px solid #ddd;
            padding-left: 10px;
        }
        
        .option-correct {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }
        
        .option-text {
            font-size: 12px;
        }
        
        .correct-marker {
            font-family: 'DejaVu Sans';
            color: #28a745;
            font-weight: bold;
            margin-right: 5px;
        }
        
        .packages-list {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }
        
        .no-questions {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ asset('backend/fonts/DejaVuSans.ttf') }}') format('truetype');
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">
            Generated on {{ date('F j, Y \a\t g:i A') }}
        </div>
    </div>

    <div class="info-section">
        <h3>Export Information</h3>
        <div class="info-row">
            <span class="info-label">Total Questions:</span>
            <span>{{ $questions->count() }}</span>
        </div>
        @if($packageId)
        <div class="info-row">
            <span class="info-label">Package:</span>
            <span>{{ $package->name ?? 'Unknown Package' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Package Description:</span>
            <span>{{ $package->description ?? 'No description' }}</span>
        </div>
        @else
        <div class="info-row">
            <span class="info-label">Filter:</span>
            <span>All Questions</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Question Types:</span>
            <span>{{ $questions->pluck('question_type')->unique()->map(function($type) { return ucwords(str_replace('_', ' ', $type)); })->join(', ') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Points:</span>
            <span>{{ $questions->sum('points') }}</span>
        </div>
    </div>

    @if($questions->count() > 0)
        @foreach($questions as $index => $question)
            <div class="question-item">
                <div class="question-header">
                    <div class="question-number">Question {{ $index + 1 }}</div>
                    <div class="question-meta">
                        {{ ucwords(str_replace('_', ' ', $question->question_type)) }} • 
                        {{ $question->points }} points
                        @if($packageId)
                            • {{ $question->packages->contains($packageId) ? 'In Package' : 'Not in Package' }}
                        @endif
                    </div>
                </div>
                
                <div class="question-text">
                    {!! $question->question_text !!}
                </div>
                
                @if($question->isMultipleChoice() && $question->options->count() > 0)
                    <div class="question-options">
                        <strong>Options:</strong>
                        @foreach($question->options->sortBy('order') as $option)
                            <div class="option-item {{ $option->is_correct ? 'option-correct' : '' }}">
                                <span class="option-text">
                                    @if($option->is_correct)
                                        <span class="correct-marker">&#10003;</span>
                                    @endif
                                    {{ $option->option_text }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @elseif($question->isForcedChoice())
                    <div class="question-options">
                        <strong>Traits/Options:</strong>
                        @php
                            $traits = $question->getForcedChoiceTraits();
                        @endphp
                        @if(!empty($traits))
                            @foreach($traits as $trait)
                                <div class="option-item">
                                    <span class="option-text">{{ $trait }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="option-item">
                                <span class="option-text text-muted">No traits defined</span>
                            </div>
                        @endif
                    </div>
                @endif
                
                @if($question->packages->count() > 0)
                    <div class="packages-list">
                        <strong>Packages:</strong> {{ $question->packages->pluck('name')->join(', ') }}
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="no-questions">
            <h3>No questions found</h3>
            <p>There are no questions to display for the selected criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This document was generated automatically by ZonaKarya ATS System</p>
        <p>© {{ date('Y') }} ZonaKarya. All rights reserved.</p>
    </div>
</body>
</html>
