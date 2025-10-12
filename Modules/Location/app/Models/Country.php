<?php

namespace Modules\Location\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shop\app\Models\DeliveryAddress;

class Country extends Model {
    use HasFactory;

    protected $fillable = [
        'slug', 'status',
    ];

    public function getNameAttribute(): ?string {
        return $this?->translation?->name;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(CountryTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?CountryTranslation {
        return $this->hasOne(CountryTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(CountryTranslation::class, 'country_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function delivery_addresses(): ?HasMany {
        return $this->hasMany(DeliveryAddress::class, 'country_id');
    }
    public function users(): HasMany {
        return $this->hasMany(User::class, 'country_id');
    }
}
