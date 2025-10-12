<?php

namespace Modules\Marquee\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsTickerTranslation extends Model {
    use HasFactory;

    protected $fillable = [
        'news_ticker_id',
        'lang_code',
        'title',
    ];

    public function news_ticker(): ?BelongsTo {
        return $this->belongsTo(NewsTicker::class, 'news_ticker_id');
    }
}
