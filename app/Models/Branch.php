<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'address',
        'city',
        'province',
        'map',
        'description',
        'section_title',
        'section_description',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the service that owns the branch.
     */
    public function service()
    {
        return $this->belongsTo(\Modules\Service\app\Models\Service::class);
    }

    /**
     * Scope a query to only include active branches.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order branches by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}