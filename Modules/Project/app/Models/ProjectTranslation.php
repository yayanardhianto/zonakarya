<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['project_id', 'lang_code', 'title', 'description', 'project_category', 'seo_title', 'seo_description'];
    public function project(): ?BelongsTo {
        return $this->belongsTo(Project::class);
    }
}
