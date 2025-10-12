<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Talent extends Model
{
    protected $table = 'talents';
    
    protected $fillable = [
        'name',
        'city',
        'level_potential',
        'talent_potential',
        'attitude_level',
        'potential_level',
        'potential_position',
        'communication',
        'initiative',
        'leadership',
        'notes',
        'applicant_id',
        'user_id'
    ];

    protected $casts = [
        'attitude_level' => 'integer',
        'potential_level' => 'integer',
        'communication' => 'integer',
        'initiative' => 'integer',
        'leadership' => 'integer',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'user_id');
    }
}
