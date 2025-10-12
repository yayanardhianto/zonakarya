<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Project\app\Models\Project;

class Service extends Model {
    use HasFactory;

    protected $fillable = [
        'slug',
        'icon',
        'is_popular',
        'image',
        'status',
    ];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function getShortDescriptionAttribute(): ?string {
        return $this?->translation?->short_description;
    }
    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }

    public function getSeoTitleAttribute(): ?string {
        return $this?->translation?->seo_title;
    }

    public function getSeoDescriptionAttribute(): ?string {
        return $this?->translation?->seo_description;
    }

    public function getBtnTextAttribute(): ?string {
        return $this?->translation?->btn_text;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(ServiceTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?ServiceTranslation {
        return $this->hasOne(ServiceTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(ServiceTranslation::class, 'service_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function scopePopular($query) {
        return $query->where('is_popular', 1);
    }
    public function projects(): ?HasMany {
        return $this->hasMany(Project::class, 'service_id');
    }

    public function branches(): ?HasMany {
        return $this->hasMany(\App\Models\Branch::class, 'service_id');
    }

}
