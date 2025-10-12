<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'answer_text',
        'video_answer',
        'selected_option_id',
        'scale_value',
        'is_correct',
        'points_earned',
        'answered_at'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer',
        'scale_value' => 'integer',
        'answered_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(TestQuestionOption::class, 'selected_option_id');
    }

    public function isMultipleChoice()
    {
        return $this->question->isMultipleChoice();
    }

    public function isEssay()
    {
        return $this->question->isEssay();
    }

    public function isScale()
    {
        return $this->question->isScale();
    }

    public function isVideoRecord()
    {
        return $this->question->isVideoRecord();
    }
}