<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\app\Http\Controllers\ProjectController;
use Modules\Project\app\Http\Controllers\ProjectUtilityController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('project', ProjectController::class)->names('project')->except('show');
    Route::put('/project/status-update/{id}', [ProjectController::class, 'statusUpdate'])->name('project.status-update');

    Route::controller(ProjectUtilityController::class)->group(function () {
        Route::get('project-gallery/{id}', 'showGallery')->name('project.gallery');
        Route::put('project-gallery/{id}', 'updateGallery')->name('project.gallery.update');
        Route::delete('project-gallery/{id}', 'deleteGallery')->name('project.gallery.delete');
    });
});
