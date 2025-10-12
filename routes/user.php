<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ApplicantStatusController;

Route::get('/dashboard', fn() => to_route('user.dashboard'))->name('dashboard');

Route::middleware(['auth:web', 'verified', 'translation', 'maintenance.mode'])->name('user.')->prefix('user')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('update-password', [ProfileController::class, 'change_password'])->name('change-password');
    Route::post('update-password', [ProfileController::class, 'update_password'])->name('update-password');
    Route::post('update-profile-image', [ProfileController::class, 'update_image'])->name('update.profile-image');
    
    // Applicant status routes
    Route::get('applications', [ApplicantStatusController::class, 'index'])->name('applications');
    Route::get('applications/{application}', [ApplicantStatusController::class, 'show'])->name('applications.show');

});