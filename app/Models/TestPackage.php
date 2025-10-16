<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'duration_minutes',
        'total_questions',
        'passing_score',
        'show_score_to_user',
        'is_active',
        'applicant_flow_order',
        'is_applicant_flow',
        'is_screening_test',
        'question_order',
        'enable_time_per_question',
        'time_per_question_seconds',
        'randomize_questions',
        'fix_first_question',
        'fix_last_question',
        'fixed_first_question_id',
        'fixed_last_question_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_score_to_user' => 'boolean',
        'duration_minutes' => 'integer',
        'total_questions' => 'integer',
        'passing_score' => 'integer',
        'applicant_flow_order' => 'integer',
        'is_applicant_flow' => 'boolean',
        'is_screening_test' => 'boolean',
        'question_order' => 'array',
        'enable_time_per_question' => 'boolean',
        'time_per_question_seconds' => 'integer',
        'randomize_questions' => 'boolean',
        'fix_first_question' => 'boolean',
        'fix_last_question' => 'boolean',
        'fixed_first_question_id' => 'integer',
        'fixed_last_question_id' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function category()
    {
        return $this->belongsTo(TestCategory::class, 'category_id');
    }

    public function questions()
    {
        return $this->belongsToMany(TestQuestion::class, 'test_package_question', 'test_package_id', 'test_question_id')
                    ->withPivot('order', 'time_per_question_seconds')
                    ->select('test_questions.*');
    }

    // Keep the old relationship for backward compatibility
    public function directQuestions()
    {
        return $this->hasMany(TestQuestion::class, 'package_id')->orderBy('order');
    }

    public function sessions()
    {
        return $this->hasMany(TestSession::class, 'package_id');
    }

    public function fixedFirstQuestion()
    {
        return $this->belongsTo(TestQuestion::class, 'fixed_first_question_id');
    }

    public function fixedLastQuestion()
    {
        return $this->belongsTo(TestQuestion::class, 'fixed_last_question_id');
    }

    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        }
        
        return $minutes . ' menit';
    }

    // Applicant Flow Methods
    public function isScreeningTest()
    {
        return $this->is_screening_test;
    }

    public function isApplicantFlowTest()
    {
        return $this->is_applicant_flow;
    }

    public function getApplicantFlowOrder()
    {
        return $this->applicant_flow_order;
    }

    // Question Order Management
    public function getOrderedQuestions()
    {
        if ($this->randomize_questions) {
            return $this->getRandomizedQuestionsWithFixed();
        }

        // For non-randomize mode, always use pivot.order regardless of fixed questions
        return $this->questions()->orderBy('test_package_question.order')->get();
    }

    private function getRandomizedQuestionsWithFixed()
    {
        $allQuestions = $this->questions()->get();
        $fixedFirst = null;
        $fixedLast = null;
        $randomQuestions = collect();

        // Get fixed questions if enabled
        if ($this->fix_first_question && $this->fixed_first_question_id) {
            $fixedFirst = $allQuestions->where('id', $this->fixed_first_question_id)->first();
        }

        if ($this->fix_last_question && $this->fixed_last_question_id) {
            $fixedLast = $allQuestions->where('id', $this->fixed_last_question_id)->first();
        }

        // Get remaining questions for randomization
        $remainingQuestions = $allQuestions->reject(function ($question) use ($fixedFirst, $fixedLast) {
            return ($fixedFirst && $question->id === $fixedFirst->id) || 
                   ($fixedLast && $question->id === $fixedLast->id);
        });

        // Randomize remaining questions
        $randomQuestions = $remainingQuestions->shuffle();

        // Build final order
        $orderedQuestions = collect();

        // Add fixed first question
        if ($fixedFirst) {
            $orderedQuestions->push($fixedFirst);
        }

        // Add randomized questions
        $orderedQuestions = $orderedQuestions->merge($randomQuestions);

        // Add fixed last question
        if ($fixedLast) {
            $orderedQuestions->push($fixedLast);
        }


        return $orderedQuestions;
    }


    public function setQuestionOrder(array $questionIds)
    {
        $this->update(['question_order' => $questionIds]);
    }

    public function addQuestionToOrder($questionId, $position = null)
    {
        $currentOrder = $this->question_order ?? [];
        
        if ($position === null) {
            $currentOrder[] = $questionId;
        } else {
            array_splice($currentOrder, $position, 0, $questionId);
        }
        
        $this->update(['question_order' => $currentOrder]);
    }

    public function removeQuestionFromOrder($questionId)
    {
        $currentOrder = $this->question_order ?? [];
        $newOrder = array_values(array_filter($currentOrder, function($id) use ($questionId) {
            return $id != $questionId;
        }));
        
        $this->update(['question_order' => $newOrder]);
    }

    public function getTotalDuration()
    {
        // Check if time per question is enabled and has custom times
        if ($this->enable_time_per_question) {
            $hasCustomTime = $this->questions()->wherePivot('time_per_question_seconds', '!=', null)->exists();
            
            if ($hasCustomTime) {
                // Calculate total time based on individual question times
                $totalSeconds = $this->questions()->sum('test_package_question.time_per_question_seconds');
                return $totalSeconds;
            }
        }
        
        return $this->duration_minutes * 60; // Convert to seconds
    }

    public function getDurationFormattedWithQuestionTime()
    {
        // Check if time per question is enabled and has custom times
        if ($this->enable_time_per_question) {
            $hasCustomTime = $this->questions()->wherePivot('time_per_question_seconds', '!=', null)->exists();
            
            if ($hasCustomTime) {
                $totalSeconds = $this->questions()->sum('test_package_question.time_per_question_seconds');
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
                
                $result = '';
                if ($hours > 0) $result .= $hours . ' jam ';
                if ($minutes > 0) $result .= $minutes . ' menit ';
                if ($seconds > 0) $result .= $seconds . ' detik ';
                
                return trim($result) . ' (custom per question)';
            }
        }
        
        return $this->getDurationFormattedAttribute();
    }

    public function setQuestionTime($questionId, $timeInSeconds)
    {
        $result = $this->questions()->updateExistingPivot($questionId, [
            'time_per_question_seconds' => $timeInSeconds,
            'updated_at' => now()
        ]);
        
        // Log for debugging
        \Log::info("Setting question time for question {$questionId}: {$timeInSeconds} seconds. Result: " . ($result ? 'success' : 'failed'));
        
        return $result;
    }

    public function getQuestionTime($questionId)
    {
        $pivot = $this->questions()->where('test_questions.id', $questionId)->first();
        return $pivot ? $pivot->pivot->time_per_question_seconds : null;
    }

    public function hasCustomQuestionTimes()
    {
        return $this->questions()->wherePivot('time_per_question_seconds', '!=', null)->exists();
    }

    public function updateTotalQuestions()
    {
        $this->update(['total_questions' => $this->questions()->count()]);
    }
}