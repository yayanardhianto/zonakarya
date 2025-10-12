<?php

namespace Modules\Location\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'country_id',
        'lang_code',
        'name',
    ];

    public function country(): ?BelongsTo {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
