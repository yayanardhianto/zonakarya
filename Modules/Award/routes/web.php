<?php

use Illuminate\Support\Facades\Route;
use Modules\Award\app\Http\Controllers\AwardController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('award', [AwardController::class, 'index'])->name('award.index');
    Route::post('award', [AwardController::class, 'store'])->name('award.store');
    Route::post('award/{id}', [AwardController::class, 'update'])->name('award.update');
    Route::delete('award/{id}', [AwardController::class, 'destroy'])->name('award.destroy');
    Route::put('award/status-update/{id}', [AwardController::class, 'statusUpdate'])->name('award.status-update');
});
