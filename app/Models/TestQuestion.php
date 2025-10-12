<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_image',
        'question_type',
        'points',
        'order'
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
    ];

    public function packages()
    {
        return $this->belongsToMany(TestPackage::class, 'test_package_question', 'test_question_id', 'test_package_id')
                    ->withPivot('order')
                    ->orderBy('test_package_question.order');
    }

    // Keep the old relationship for backward compatibility
    public function package()
    {
        return $this->belongsTo(TestPackage::class, 'package_id');
    }

    public function options()
    {
        return $this->hasMany(TestQuestionOption::class, 'question_id')->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'question_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->question_image) {
            return asset('uploads/store/' . $this->question_image);
        }
        return null;
    }

    public function isMultipleChoice()
    {
        return $this->question_type === 'multiple_choice';
    }

    public function isEssay()
    {
        return $this->question_type === 'essay';
    }

    public function isScale()
    {
        return $this->question_type === 'scale';
    }

    public function isVideoRecord()
    {
        return $this->question_type === 'video_record';
    }

    public function isForcedChoice()
    {
        return $this->question_type === 'forced_choice';
    }

    public function getForcedChoiceInstruction()
    {
        if (!$this->isForcedChoice()) {
            return $this->question_text;
        }

        // Check if it's new format with TRAITS_JSON comment
        if (strpos($this->question_text, '<!--TRAITS_JSON:') !== false) {
            // New format: extract instruction from question_text (before TRAITS_JSON comment)
            $parts = explode('<!--TRAITS_JSON:', $this->question_text);
            return trim($parts[0]);
        } else {
            // Old format: question_text is just JSON array, return default instruction
            return 'Pilih 1 sifat yang PALING MIRIP dengan gambaran diri Anda, dan 1 sifat yang PALING TIDAK MIRIP dengan gambaran diri Anda dari daftar di bawah ini.';
        }
    }

    public function getForcedChoiceTraits()
    {
        if (!$this->isForcedChoice()) {
            return [];
        }

        // Check if it's new format with TRAITS_JSON comment
        if (preg_match('/<!--TRAITS_JSON:(.*?)-->/s', $this->question_text, $matches)) {
            // New format: extract traits from comment
            return json_decode($matches[1], true) ?? [];
        } else {
            // Old format: question_text is just JSON array
            return json_decode($this->question_text, true) ?? [];
        }
    }
}