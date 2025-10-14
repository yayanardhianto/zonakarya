<?php

use Illuminate\Support\Facades\Route;
use Modules\OurTeam\app\Http\Controllers\OurTeamController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('ourteam', OurTeamController::class)->names('ourteam')->except('show');
    Route::put('contact/ourteam', [OurTeamController::class, 'contactOurTeam'])->name('contact.ourteam');
    Route::put('ourteam/status-update/{id}', [OurTeamController::class, 'statusUpdate'])->name('ourteam.status-update');
    Route::post('ourteam/update-section-title', [OurTeamController::class, 'updateSectionTitle'])->name('ourteam.update-section-title');
});
