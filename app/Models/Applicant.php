<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'whatsapp',
        'cv_path',
        'photo_path',
        'status',
        'notes',
        'last_stage',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function talent(): HasMany
    {
        return $this->hasMany(Talent::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'sent' => 'info',
            'check' => 'primary',
            'short_call' => 'success',
            'individual_interview' => 'success',
            'group_interview' => 'success',
            'test_psychology' => 'info',
            'ojt' => 'warning',
            'final_interview' => 'primary',
            'sent_offering_letter' => 'success',
            'onboard' => 'success',
            'rejected' => 'danger',
            'rejected_by_applicant' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'sent' => 'Test Screening',
            'check' => 'Check',
            'short_call' => 'Short Call',
            'individual_interview' => 'Individual Interview',
            'group_interview' => 'Group Interview',
            'test_psychology' => 'Test Psychology',
            'ojt' => 'OJT',
            'final_interview' => 'Final Interview',
            'sent_offering_letter' => 'Sent Offering Letter',
            'onboard' => 'Onboard',
            'rejected' => 'Rejected',
            'rejected_by_applicant' => 'Rejected by Applicant',
            default => 'Unknown'
        };
    }

    // Test Session Methods
    public function testSessions()
    {
        return $this->hasMany(TestSession::class, 'applicant_id');
    }

    public function getScreeningTestResult()
    {
        return $this->testSessions()
            ->whereHas('package', function($query) {
                $query->where('is_screening_test', true);
            })
            ->where('status', 'completed')
            ->first();
    }

    public function hasCompletedScreening()
    {
        return $this->getScreeningTestResult() !== null;
    }

    public function getPsychologyTestResult()
    {
        return $this->testSessions()
            ->whereHas('package', function($query) {
                $query->where('applicant_flow_order', 2);
            })
            ->where('status', 'completed')
            ->first();
    }

    public function hasCompletedPsychology()
    {
        return $this->getPsychologyTestResult() !== null;
    }
}
