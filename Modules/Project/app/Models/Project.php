<?php

namespace Modules\Project\app\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\app\Models\Service;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['slug', 'service_id', 'image', 'project_date','project_author', 'tags', 'status'];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }
    public function getProjectCategoryAttribute(): ?string {
        return $this?->translation?->project_category;
    }

    public function getSeoTitleAttribute(): ?string {
        return $this?->translation?->seo_title;
    }

    public function getSeoDescriptionAttribute(): ?string {
        return $this?->translation?->seo_description;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(ProjectTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?ProjectTranslation {
        return $this->hasOne(ProjectTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(ProjectTranslation::class, 'project_id');
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function service(): ?BelongsTo {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function images() {
        return $this->hasMany(ProjectImage::class);
    }
    protected static function boot() {
        parent::boot();

        static::deleting(function ($project) {
            try {
                if ($project->images) {
                    $project->images()->each(function ($image) {
                        if ($image->large_image && !str($image->large_image)->contains('website/images')) {
                            if (@File::exists(public_path($image->large_image))) {
                                @unlink(public_path($image->large_image));
                            }
                        }
                        if ($image->small_image && !str($image->small_image)->contains('website/images')) {
                            if (@File::exists(public_path($image->small_image))) {
                                @unlink(public_path($image->small_image));
                            }
                        }
                        $image->project()->dissociate();
                        $image->delete();
                    });
                }
                if ($project->translations) {
                    $project->translations()->each(function ($translation) {
                        if ($translation?->description) {
                            deleteUnusedUploadedImages($translation?->description);
                        }
                        $translation->project()->dissociate();
                        $translation->delete();
                    });
                }
            } catch (\Exception $e) {
                info($e);

                $notification = __('Unable to delete as relational data exists!');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }
        });
    }

}
