<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BlogController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\HomePageController;

Route::group(['middleware' => ['maintenance.mode', 'translation']], function () {

    Route::controller(HomePageController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('change-theme/{name}', 'changeTheme')->name('change-theme');
        Route::view('contact','frontend.pages.contact')->name('contact');
        Route::get('team', 'team')->name('team');
        Route::get('team/{slug}', 'singleTeam')->name('single.team');
        Route::post('contact/member/{slug}', 'contactTeamMember')->name('contact.team.member')->middleware('throttle:4,60');
        Route::get('about', 'about')->name('about');
        Route::get('faq', 'faq')->name('faq');
        
        Route::get('portfolios', 'portfolios')->name('portfolios');
        Route::get('portfolios/{slug}', 'singlePortfolio')->name('single.portfolio');
        Route::get('services', 'services')->name('services');
        Route::get('services/{slug}', 'singleService')->name('single.service');
        
        Route::get('privacy-policy', 'privacyPolicy')->name('privacy-policy');
        Route::get('terms-condition', 'termsCondition')->name('terms-condition');
        Route::get('page/{slug}', 'customPage')->name('custom-page');
    });
    Route::controller(BlogController::class)->group(function () {
        Route::get('blogs', 'index')->name('blogs');
        Route::get('blogs/{slug}', 'show')->name('single.blog');
        Route::post('blogs/{slug}', 'blogCommentStore')->name('blog.comment.store')->middleware(['auth:web', 'verified']);
    });


});
Route::get('set-language', [DashboardController::class, 'setLanguage'])->name('set-language');


//maintenance mode route
Route::get('maintenance-mode', function () {
    $setting = cache()->get('setting', null);
    return $setting?->maintenance_mode ? view('global.maintenance') : redirect()->route('home');
})->name('maintenance.mode');

// Social Auth Routes - specific routes for applicant flow
Route::get('auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('auth/linkedin', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToLinkedIn'])->name('auth.linkedin');
Route::get('auth/linkedin/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleLinkedInCallback'])->name('auth.linkedin.callback');

// Debug route for testing
Route::get('debug/social-login', function() {
    return response()->json([
        'message' => 'Social login debug',
        'applicant_id' => request('applicant_id'),
        'all_params' => request()->all(),
        'google_redirect' => route('auth.google') . '?applicant_id=6',
        'google_callback' => route('auth.google.callback') . '?applicant_id=6'
    ]);
});

// Job Vacancy Routes
Route::get('jobs', [\App\Http\Controllers\Frontend\JobVacancyController::class, 'index'])->name('jobs.index');
Route::get('jobs/search', [\App\Http\Controllers\Frontend\JobVacancyController::class, 'search'])->name('jobs.search');
Route::get('jobs/{jobVacancy}', [\App\Http\Controllers\Frontend\JobVacancyController::class, 'show'])->name('jobs.show');
Route::get('jobs/thank-you/{applicant}', [\App\Http\Controllers\Frontend\JobVacancyController::class, 'thankYou'])->name('jobs.thank-you');

// Job Application Routes
Route::post('jobs/{jobVacancy}/apply', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'storeApplication'])->name('jobs.apply.store');
// Preliminary apply (step 1): create applicant+application and return start_test_url
Route::post('jobs/{jobVacancy}/apply-prelim', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'applyPrelim'])->name('jobs.apply.prelim');
// Direct profile apply (skip test flow): create application and go to profile
Route::post('jobs/{jobVacancy}/apply-direct-profile', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'applyDirectProfile'])->name('jobs.apply.direct-profile');
// Finalize application (step 2): upload CV and photo after test
Route::post('applications/{application}/finalize', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'finalizeApplication'])->name('applications.finalize');
Route::post('applications/complete-registration', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'completeRegistration'])->name('applications.complete-registration');

// Applicant profile page (replaces finalize modal after test)
Route::get('applications/{application}/profile', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'showProfile'])->name('applications.profile');
// Applicant profile page for skip test flow (no application yet)
Route::get('jobs/{jobVacancy}/profile', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'showProfileSkipTest'])->name('jobs.profile.skip-test');
// Submit profile for skip test flow (creates applicant and application)
Route::post('jobs/{id}/submit-profile-skip-test', [\App\Http\Controllers\Frontend\JobApplicationController::class, 'submitProfileSkipTestById'])->name('jobs.profile.skip-test.submit.by-id')->middleware('auth:web');

// Applicant Status Routes
Route::get('applicant/status', [\App\Http\Controllers\Frontend\ApplicantController::class, 'status'])->name('applicant.status');

// Debug route for testing
Route::get('debug/applicant/status', function() {
    $user = \App\Models\User::find(12);
    if (!$user) {
        return 'User not found';
    }
    
    $applications = \App\Models\Application::with(['applicant', 'jobVacancy', 'testSession.package'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();
    
    return view('frontend.applicant.status', compact('applications', 'user'));
});

// Test WhatsApp API (for testing only)
Route::get('test-whatsapp/{phone}', function($phone) {
    $whatsappService = new \App\Services\WhatsAppService();
    $message = "Test message from ATS system. Time: " . now()->format('d M Y H:i:s');
    $result = $whatsappService->sendMessage($phone, $message);
    
    return response()->json([
        'phone' => $phone,
        'message' => $message,
        'result' => $result
    ]);
})->name('test.whatsapp');





// Test Routes - Mixed access (some require auth, some are public)
Route::middleware(['auth:web'])->group(function () {
    Route::get('tests', [\App\Http\Controllers\Frontend\TestController::class, 'index'])->name('test.index');
    Route::get('tests/start/{package}', [\App\Http\Controllers\Frontend\TestController::class, 'start'])->name('test.start');
    Route::get('tests/regenerate-token/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'regenerateToken'])->name('test.regenerate-token');
    Route::get('tests/qr-code/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'generateQRCode'])->name('test.qr-code');
});

// Public Test Routes (accessible with valid token)
Route::get('tests/take/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'take'])->name('test.take');
Route::get('tests/result/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'result'])->name('test.result');
Route::post('tests/answer/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'answer'])->name('test.answer');
Route::post('tests/complete/{session}', [\App\Http\Controllers\Frontend\TestController::class, 'complete'])->name('test.complete');
Route::post('test/upload-video', [\App\Http\Controllers\Frontend\TestController::class, 'uploadVideo'])->name('test.upload-video');

// Public Package Routes (shareable package links)
Route::get('test/package/{package}', [\App\Http\Controllers\Frontend\TestController::class, 'publicPackage'])->name('test.public-package');

// Custom Auth Routes
Route::post('custom-login', [\App\Http\Controllers\Frontend\CustomAuthController::class, 'login'])->name('custom.login');

require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
require __DIR__ . '/admin.php';

// Short URL Routes (must be after admin routes, use regex constraint to avoid catching admin paths)
Route::get('{code}', [\App\Http\Controllers\ShortUrlRedirectController::class, 'redirect'])
    ->where('code', '^[a-zA-Z0-9-]{3,50}$')
    ->name('short-url.redirect');

Route::fallback(function () {
    abort(404);
});