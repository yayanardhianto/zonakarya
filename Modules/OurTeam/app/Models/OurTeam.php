<?php

namespace Modules\OurTeam\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status', 'slug'];
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

}
