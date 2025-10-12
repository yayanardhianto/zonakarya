<?php

use Illuminate\Support\Facades\Route;
use Modules\SocialLink\app\Http\Controllers\SocialLinkController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('social-link', SocialLinkController::class)->names('social-link');
});
