<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\app\Http\Controllers\ServiceController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('service', ServiceController::class)->names('service')->except('show');
    Route::put('/service/status-update/{id}', [ServiceController::class, 'statusUpdate'])->name('service.status-update');
});
