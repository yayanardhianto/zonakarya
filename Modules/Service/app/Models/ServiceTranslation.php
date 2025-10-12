<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Database\factories\ServiceTranslationFactory;

class ServiceTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service_id',
        'lang_code',
        'title',
        'short_description',
        'description',
        'seo_title',
        'btn_text',
        'seo_description',
    ];

    public function service(): ?BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
