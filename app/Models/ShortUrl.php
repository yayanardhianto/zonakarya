<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortUrl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'original_url', 'created_by', 'click_count'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the admin who created this short URL
     */
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Generate a random short code
     */
    public static function generateUniqueCode($length = 6)
    {
        do {
            $code = \Str::random($length);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Increment click count
     */
    public function recordClick()
    {
        $this->increment('click_count');
    }

    /**
     * Get the short URL
     */
    public function getShortUrlAttribute()
    {
        return config('app.url') . '/' . $this->code;
    }
}
