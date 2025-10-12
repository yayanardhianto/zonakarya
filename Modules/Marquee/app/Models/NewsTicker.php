<?php

namespace Modules\Marquee\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Marquee\app\Models\NewsTickerTranslation;

class NewsTicker extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
    ];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(NewsTickerTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?NewsTickerTranslation {
        return $this->hasOne(NewsTickerTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(NewsTickerTranslation::class, 'news_ticker_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
