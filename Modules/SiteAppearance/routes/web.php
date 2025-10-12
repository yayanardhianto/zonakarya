<?php

use Illuminate\Support\Facades\Route;
use Modules\SiteAppearance\app\Http\Controllers\SectionSettingController;
use Modules\SiteAppearance\app\Http\Controllers\SiteAppearanceController;
use Modules\SiteAppearance\app\Http\Controllers\SiteColorController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::get('site-appearance', [SiteAppearanceController::class,'index'])->name('site-appearance.index');
    Route::put('site-appearance', [SiteAppearanceController::class,'update'])->name('site-appearance.update');
    Route::put('show-all-homepage', [SiteAppearanceController::class,'showAllHomePage'])->name('show.all.homepage');

    Route::resource('section-settings', SectionSettingController::class)->names('section-setting');
    Route::resource('site-color-settings', SiteColorController::class)->names('site-color-setting');
});
