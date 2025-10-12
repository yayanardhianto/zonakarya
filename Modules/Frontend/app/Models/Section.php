<?php

namespace Modules\Frontend\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Section extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['home_id', 'name', 'global_content', 'status', 'order', 'is_active'];
    protected $casts = [
        'global_content' => 'array',
    ];
    public function getGlobalContentAttribute($value): object|null {
        if (empty($value) || $value === '[]' || $value === '{}') {
            return null;
        }
        $decoded = json_decode($value);
        if ($decoded === null) {
            return null;
        }
        return is_object($decoded) ? $decoded : (object) $decoded;
    }
    
    public function getContentAttribute():object|null {
        return $this->translation?->content;
    }
    public function translation(): ?HasOne {
        return $this->hasOne(SectionTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?SectionTranslation {
        return $this->hasOne(SectionTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(SectionTranslation::class, 'section_id');
    }
    public function home(): BelongsTo {
        return $this->belongsTo(Home::class, 'home_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function scopeInactive($query) {
        return $query->where('status', 0);
    }
    public function scopeOrdered($query) {
        return $query->orderBy('order', 'asc');
    }
    public function scopeActiveSections($query) {
        return $query->where('is_active', true);
    }
    static function getByName(string $section_name, string $theme_name = DEFAULT_HOMEPAGE) {
        $home = Home::firstOrCreate(['slug' => $theme_name]);
        $section = self::firstOrCreate(['name' => $section_name,'home_id' => $home->id]);
        return $section;
    }
}
