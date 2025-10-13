<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_code',
        'position',
        'location',
        'location_id',
        'work_type',
        'salary_min',
        'salary_max',
        'education',
        'gender',
        'age_min',
        'age_max',
        'experience_years',
        'specific_requirements',
        'description',
        'responsibilities',
        'benefits',
        'company_name',
        'company_logo',
        'contact_email',
        'contact_phone',
        'status',
        'application_deadline',
        'views',
        'show_salary',
        'show_age'
    ];

    protected $casts = [
        'specific_requirements' => 'array',
        'application_deadline' => 'date',
        'show_salary' => 'boolean',
        'show_age' => 'boolean',
    ];

    // Scope untuk lowongan aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk lowongan yang masih berlaku
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now()->toDateString());
                    });
    }

    // Accessor untuk format gaji
    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return 'Rp ' . number_format($this->salary_min, 0, ',', '.') . ' - Rp ' . number_format($this->salary_max, 0, ',', '.');
        } elseif ($this->salary_min) {
            return 'Rp ' . number_format($this->salary_min, 0, ',', '.') . ' ke atas';
        } elseif ($this->salary_max) {
            return 'Hingga Rp ' . number_format($this->salary_max, 0, ',', '.');
        }
        return 'Gaji sesuai kesepakatan';
    }

    // Accessor untuk mendapatkan kota saja
    public function getCityAttribute()
    {
        return explode(',', $this->location)[0];
    }

    // Accessor untuk format usia
    public function getFormattedAgeAttribute()
    {
        if ($this->age_min && $this->age_max) {
            return $this->age_min . ' - ' . $this->age_max . ' tahun';
        } elseif ($this->age_min) {
            return $this->age_min . ' tahun ke atas';
        } elseif ($this->age_max) {
            return 'Hingga ' . $this->age_max . ' tahun';
        }
        return 'Tidak ada batasan usia';
    }

    // Method untuk increment views
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Relasi dengan location
    public function locationBranch()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Relasi dengan applications
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Generate unique code
    public static function generateUniqueCode()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        do {
            // Generate 2 random letters
            $code = '';
            for ($i = 0; $i < 2; $i++) {
                $code .= $letters[rand(0, strlen($letters) - 1)];
            }
            
            // Generate 2 random numbers
            for ($i = 0; $i < 2; $i++) {
                $code .= $numbers[rand(0, strlen($numbers) - 1)];
            }
            
            // Shuffle the characters to make it non-sequential
            $codeArray = str_split($code);
            shuffle($codeArray);
            $code = implode('', $codeArray);
            
        } while (self::where('unique_code', $code)->exists());
        
        return $code;
    }

    // Route model binding using unique_code
    public function getRouteKeyName()
    {
        return 'unique_code';
    }
}
