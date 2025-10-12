<?php

namespace Modules\Award\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Award extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['url', 'status'];

    public function getYearAttribute(): ?string {
        return $this?->translation?->year;
    }
    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }
    public function getSubTitleAttribute(): ?string {
        return $this?->translation?->sub_title;
    }
    public function getTagAttribute(): ?string {
        return $this?->translation?->tag;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(AwardTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?AwardTranslation {
        return $this->hasOne(AwardTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(AwardTranslation::class, 'award_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
