<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'applicant_id',
        'job_vacancy_id',
        'test_session_id',
        'status',
        'last_stage',
        'notes',
        'interviewer_id',
        'test_sent_at',
        'test_completed_at',
        'test_score',
        'whatsapp_message',
    ];

    protected $casts = [
        'test_sent_at' => 'datetime',
        'test_completed_at' => 'datetime',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(Interviewer::class, 'interviewer_id');
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

    public function getLastStageTextAttribute(): string
    {
        if (!$this->last_stage) {
            return $this->status_text;
        }
        
        return match($this->last_stage) {
            'pending' => __('Pending'),
            'sent' => __('Test Screening'),
            'check' => __('Check'),
            'short_call' => __('Short Call'),
            'individual_interview' => __('Individual Interview'),
            'group_interview' => __('Group Interview'),
            'test_psychology' => __('Test Psychology'),
            'ojt' => __('OJT'),
            'final_interview' => __('Final Interview'),
            'sent_offering_letter' => __('Sent Offering Letter'),
            'onboard' => __('Onboard'),
            'rejected' => __('Rejected'),
            'rejected_by_applicant' => __('Rejected by Applicant'),
            default => ucfirst(str_replace('_', ' ', $this->last_stage))
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
}
