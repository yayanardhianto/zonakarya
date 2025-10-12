<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'city', 'province', 'postal_code', 
        'phone', 'email', 'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope untuk lokasi aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk nama lengkap lokasi
    public function getFullNameAttribute()
    {
        return $this->name . ' - ' . $this->city . ', ' . $this->province;
    }

    // Relasi dengan job vacancies
    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class, 'location_id');
    }
}
