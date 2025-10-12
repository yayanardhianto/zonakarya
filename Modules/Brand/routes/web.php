<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\app\Http\Controllers\BrandController;

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::put('brand/status-update/{id}', [BrandController::class, 'statusUpdate'])->name('brand.status-update');
    Route::resource('brand', BrandController::class)->names('brand');
});
