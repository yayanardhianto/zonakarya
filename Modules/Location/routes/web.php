<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\app\Http\Controllers\CountryController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('country', [CountryController::class,'index'])->name('country.index');
    Route::post('country', [CountryController::class,'store'])->name('country.store');
    Route::post('country/{id}', [CountryController::class,'update'])->name('country.update');
    Route::delete('country/{id}', [CountryController::class,'destroy'])->name('country.destroy');
    Route::put('/country/status-update/{id}', [CountryController::class, 'statusUpdate'])->name('country.status-update');
});
