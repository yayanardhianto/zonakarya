<?php

namespace Modules\Award\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwardTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['award_id', 'lang_code', 'year','title', 'sub_title', 'tag'];
    public function award(): ?BelongsTo {
        return $this->belongsTo(Award::class, 'award_id');
    }
}
