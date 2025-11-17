<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TestSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_vacancy_id',
        'applicant_id',
        'package_id',
        'job_position',
        'status',
        'started_at',
        'completed_at',
        'score',
        'is_passed',
        'notes',
        'access_token',
        'expires_at',
        'question_order'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'score' => 'integer',
        'is_passed' => 'boolean',
        'question_order' => 'array',
    ];

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function package()
    {
        return $this->belongsTo(TestPackage::class, 'package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'session_id');
    }

    public function application()
    {
        return $this->hasOne(Application::class, 'test_session_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isExpiredStatus()
    {
        return $this->status === 'expired';
    }

    public function getRemainingTimeAttribute()
    {
        if (!$this->started_at || $this->isCompleted() || $this->isExpiredStatus()) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInMinutes($this->started_at);
        $remaining = $this->package->duration_minutes - $elapsed;
        
        return max(0, $remaining);
    }
    
    public function getRemainingTimeSecondsAttribute()
    {
        if (!$this->started_at || $this->isCompleted() || $this->isExpiredStatus()) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds($this->started_at);
        $remaining = ($this->package->duration_minutes * 60) - $elapsed;
        
        return max(0, $remaining);
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->package) {
            return 0;
        }

        $totalQuestions = $this->package->total_questions;
        $answeredQuestions = $this->answers()->count();
        
        if ($totalQuestions == 0) {
            return 0;
        }

        return round(($answeredQuestions / $totalQuestions) * 100, 2);
    }

    public function generateAccessToken()
    {
        $date = Carbon::now()->format('Y-m-d');
        $hash = hash('sha256', $this->id . $date . config('app.key'));
        $this->access_token = $hash;
        $this->expires_at = Carbon::now()->endOfDay();
        $this->save();
        
        return $hash;
    }

    public function isTokenValid($token)
    {
        if (!$this->access_token || !$this->expires_at) {
            return false;
        }

        if ($this->access_token !== $token) {
            return false;
        }

        // Allow access to completed tests for result viewing
        if ($this->isCompleted()) {
            return true;
        }

        if (Carbon::now()->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->gt($this->expires_at);
    }
}