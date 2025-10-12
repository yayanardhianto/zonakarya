<?php

use Illuminate\Support\Facades\Route;
use Modules\Marquee\app\Http\Controllers\MarqueeController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('marquee', [MarqueeController::class, 'index'])->name('marquee.index');
    Route::post('marquee', [MarqueeController::class, 'store'])->name('marquee.store');
    Route::post('marquee/{id}', [MarqueeController::class, 'update'])->name('marquee.update');
    Route::delete('marquee/{id}', [MarqueeController::class, 'destroy'])->name('marquee.destroy');
    Route::put('marquee/status-update/{id}', [MarqueeController::class, 'statusUpdate'])->name('marquee.status-update');
});
