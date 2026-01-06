<?php

use App\Http\Controllers\Admin\AddonsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\TinymceImageUploadController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\TestCategoryController;
use App\Http\Controllers\Admin\TestPackageController;
use App\Http\Controllers\Admin\TestQuestionController;
use App\Http\Controllers\Admin\TestSessionController;
use App\Http\Controllers\Admin\BranchController;

/*  End Admin panel Controller  */

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'translation'], function () {
    /* Start admin auth route */
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('store-login', [AuthenticatedSessionController::class, 'store'])->name('store-login');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forget-password', [PasswordResetLinkController::class, 'custom_forget_password'])->name('forget-password');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'custom_reset_password_page'])->name('password.reset');
    Route::post('/reset-password-store/{token}', [NewPasswordController::class, 'custom_reset_password_store'])->name('password.reset-store');
    /* End admin auth route */

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', fn() => to_route('admin.dashboard'));
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::controller(AdminProfileController::class)->group(function () {
            Route::get('edit-profile', 'edit_profile')->name('edit-profile');
            Route::put('profile-update', 'profile_update')->name('profile-update');
            Route::put('update-password', 'update_password')->name('update-password');
        });

        Route::get('role/assign', [RolesController::class, 'assignRoleView'])->name('role.assign');
        Route::post('role/assign/{id}', [RolesController::class, 'getAdminRoles'])->name('role.assign.admin');
        Route::put('role/assign', [RolesController::class, 'assignRoleUpdate'])->name('role.assign.update');
        Route::resource('/role', RolesController::class);
        Route::resource('/role', RolesController::class);

        Route::resource('admin', AdminController::class)->except('show');
        Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin.status');
        Route::view('settings','admin.settings.settings')->name('settings');
        Route::get('sync-modules', [AddonsController::class, 'syncModules'])->name('addons.sync');

        Route::post('tinymce-upload-image', [TinymceImageUploadController::class, 'upload']);
        Route::delete('tinymce-delete-image', [TinymceImageUploadController::class, 'destroy']);
        
        // Job Vacancy Routes
        Route::resource('job-vacancy', \App\Http\Controllers\Admin\JobVacancyController::class);
        Route::patch('job-vacancy/{jobVacancy}/toggle-status', [\App\Http\Controllers\Admin\JobVacancyController::class, 'toggleStatus'])->name('job-vacancy.toggle-status');
        
        // Branch Management Routes
        Route::resource('branch', BranchController::class);
        Route::put('branch-status/{id}', [BranchController::class, 'statusUpdate'])->name('branch.status');
        Route::get('branch-wording', [BranchController::class, 'getWording'])->name('branch.get-wording');
        Route::post('branch-wording', [BranchController::class, 'updateWording'])->name('branch.update-wording');
        
        // Location Management Routes
        Route::resource('location', \App\Http\Controllers\Admin\LocationController::class);
        
        // Test Management Routes
        Route::resource('test-category', TestCategoryController::class);
        // Test Package Export Routes (must be before resource route)
        Route::get('test-package/export-excel', [TestPackageController::class, 'exportExcel'])->name('test-package.export-excel');
        Route::get('test-package/export-pdf', [TestPackageController::class, 'exportPdf'])->name('test-package.export-pdf');
        
        Route::resource('test-package', TestPackageController::class);
        
        // Test Question custom routes (must be before resource route)
        Route::post('test-question/import', [TestQuestionController::class, 'import'])->name('test-question.import');
        Route::get('test-question/export-excel', [TestQuestionController::class, 'exportExcel'])->name('test-question.export-excel');
        Route::get('test-question/export-pdf', [TestQuestionController::class, 'exportPdf'])->name('test-question.export-pdf');
        Route::get('test-question/download-excel-template', [\App\Http\Controllers\Admin\ExcelTemplateController::class, 'downloadTestQuestionTemplate'])->name('test-question.download-excel-template');
        
        Route::resource('test-question', TestQuestionController::class);
        // Test Session Export Routes (must be before resource route)
        Route::get('test-session/export-excel', [TestSessionController::class, 'exportExcel'])->name('test-session.export-excel');
        Route::get('test-session/export-pdf', [TestSessionController::class, 'exportPdf'])->name('test-session.export-pdf');
        
        Route::resource('test-session', TestSessionController::class)->only(['index', 'show', 'destroy']);
        Route::post('test-session/{testSession}/grade-essay', [TestSessionController::class, 'gradeEssay'])->name('test-session.grade-essay');
        Route::get('test-sessions/applicant/{applicantId}/{testType}', [TestSessionController::class, 'getApplicantTestDetail'])->name('test-sessions.applicant-detail');
        
        // Test Package Question Management Routes
        Route::get('test-package/{testPackage}/add-question', [TestPackageController::class, 'addQuestion'])->name('test-package.add-question');
        Route::post('test-package/{testPackage}/attach-question', [TestPackageController::class, 'attachQuestion'])->name('test-package.attach-question');
        Route::delete('test-package/{testPackage}/detach-question/{question}', [TestPackageController::class, 'detachQuestion'])->name('test-package.detach-question');
        Route::post('test-package/{testPackage}/update-question-order', [TestPackageController::class, 'updateQuestionOrder'])->name('test-package.update-question-order');
        
        // Test Package Management Routes
        Route::post('test-package/{testPackage}/duplicate', [TestPackageController::class, 'duplicate'])->name('test-package.duplicate');
        Route::post('test-package/{testPackage}/randomize-questions', [TestPackageController::class, 'randomizeQuestions'])->name('test-package.randomize-questions');
        Route::post('test-package/{testPackage}/set-custom-order', [TestPackageController::class, 'setCustomOrder'])->name('test-package.set-custom-order');
        Route::post('test-package/{testPackage}/update-fixed-question', [TestPackageController::class, 'updateFixedQuestion'])->name('test-package.update-fixed-question');
        Route::post('test-package/{testPackage}/update-question-time', [TestPackageController::class, 'updateQuestionTime'])->name('test-package.update-question-time');
        Route::post('test-package/{testPackage}/bulk-update-question-times', [TestPackageController::class, 'bulkUpdateQuestionTimes'])->name('test-package.bulk-update-question-times');
        Route::post('test-package/{testPackage}/toggle-time-per-question', [TestPackageController::class, 'toggleTimePerQuestion'])->name('test-package.toggle-time-per-question');
        
        // Test Package Link Generation Routes
        Route::post('test-package/{testPackage}/generate-test-link', [TestPackageController::class, 'generateTestLink'])->name('test-package.generate-test-link');
        Route::post('test-package/{testPackage}/generate-public-link', [TestPackageController::class, 'generatePublicPackageLink'])->name('test-package.generate-public-link');
        Route::get('test-session/{testSession}/qr-code', [TestPackageController::class, 'generateQRCode'])->name('test-session.qr-code');
        
        // Applicant Management Routes
        // Export routes must be before resource route
        Route::get('applicants/export-excel', [\App\Http\Controllers\Admin\ApplicantController::class, 'exportExcel'])->name('applicants.export-excel');
        Route::get('applicants/export-pdf', [\App\Http\Controllers\Admin\ApplicantController::class, 'exportPdf'])->name('applicants.export-pdf');
        
        Route::resource('applicants', \App\Http\Controllers\Admin\ApplicantController::class)->only(['index', 'show']);
        Route::put('applicants/{applicant}/status', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateStatus'])->name('applicants.update-status');
        Route::post('applicants/{applicant}/send-test', [\App\Http\Controllers\Admin\ApplicantController::class, 'sendTest'])->name('applicants.send-test');
        Route::post('applicants/{applicant}/next-step', [\App\Http\Controllers\Admin\ApplicantController::class, 'nextStep'])->name('applicants.next-step');
        Route::post('applicants/{applicant}/reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'reject'])->name('applicants.reject');
        Route::get('applicants/{applicant}/download-cv', [\App\Http\Controllers\Admin\ApplicantController::class, 'downloadCv'])->name('applicants.download-cv');
        Route::get('applicants/{applicant}/view-cv', [\App\Http\Controllers\Admin\ApplicantController::class, 'viewCv'])->name('applicants.view-cv');
        Route::get('applicants/{applicant}/view-photo', [\App\Http\Controllers\Admin\ApplicantController::class, 'viewPhoto'])->name('applicants.view-photo');
        Route::get('applicants/{applicant}/whatsapp-data', [\App\Http\Controllers\Admin\ApplicantController::class, 'getWhatsAppData'])->name('applicants.whatsapp-data');
        Route::post('applicants/{applicant}/individual-interview', [\App\Http\Controllers\Admin\ApplicantController::class, 'individualInterview'])->name('applicants.individual-interview');
        Route::post('applicants/{applicant}/group-interview', [\App\Http\Controllers\Admin\ApplicantController::class, 'groupInterview'])->name('applicants.group-interview');
        Route::post('applicants/{applicant}/reject-save-talent', [\App\Http\Controllers\Admin\ApplicantController::class, 'rejectSaveTalent'])->name('applicants.reject-save-talent');
        Route::post('applicants/{applicant}/test-psychology', [\App\Http\Controllers\Admin\ApplicantController::class, 'testPsychology'])->name('applicants.test-psychology');
        Route::post('applicants/{applicant}/ojt', [\App\Http\Controllers\Admin\ApplicantController::class, 'ojt'])->name('applicants.ojt');
        Route::post('applicants/{applicant}/final-interview', [\App\Http\Controllers\Admin\ApplicantController::class, 'finalInterview'])->name('applicants.final-interview');
        Route::post('applicants/{applicant}/send-offering-letter', [\App\Http\Controllers\Admin\ApplicantController::class, 'sendOfferingLetter'])->name('applicants.send-offering-letter');
        Route::post('applicants/{applicant}/accept', [\App\Http\Controllers\Admin\ApplicantController::class, 'acceptApplicant'])->name('applicants.accept');
        Route::post('applicants/{applicant}/reject-by-applicant', [\App\Http\Controllers\Admin\ApplicantController::class, 'rejectByApplicant'])->name('applicants.reject-by-applicant');
        Route::post('applicants/{applicant}/resend-offering-letter', [\App\Http\Controllers\Admin\ApplicantController::class, 'resendOfferingLetter'])->name('applicants.resend-offering-letter');
        Route::post('applicants/{applicant}/reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'reject'])->name('applicants.reject');
        Route::post('applicants/applications/{application}/update-notes', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateNotes'])->name('applicants.applications.update-notes');
        Route::post('applicants/applications/{application}/update-interviewer', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateInterviewer'])->name('applicants.applications.update-interviewer');
        Route::post('interviewers', [\App\Http\Controllers\Admin\ApplicantController::class, 'storeInterviewer'])->name('interviewers.store');
        Route::resource('interviewers', \App\Http\Controllers\Admin\InterviewerController::class)->except(['store']);
        Route::get('applicants/applications/notes', [\App\Http\Controllers\Admin\ApplicantController::class, 'getApplicationNotes'])->name('applicants.applications.get-notes');
        Route::delete('applicants/bulk-delete', [\App\Http\Controllers\Admin\ApplicantController::class, 'bulkDelete'])->name('applicants.bulk-delete');
        Route::post('applicants/bulk-reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'bulkReject'])->name('applicants.bulk-reject');
        Route::delete('applicants/{applicant}/applications/{application}', [\App\Http\Controllers\Admin\ApplicantController::class, 'destroy'])->name('applicants.applications.destroy');
        
        // Talents Routes
        Route::resource('talents', \App\Http\Controllers\Admin\TalentController::class);
        
        // Onboard Routes
        Route::get('onboard', [\App\Http\Controllers\Admin\OnboardController::class, 'index'])->name('onboard.index');
        Route::post('talents/{talent}/reapply', [\App\Http\Controllers\Admin\TalentController::class, 'reapply'])->name('talents.reapply');
        
        // WhatsApp Templates Routes
        Route::get('whatsapp-templates/get', [\App\Http\Controllers\Admin\WhatsAppTemplateController::class, 'getTemplates'])->name('whatsapp-templates.get');
        Route::resource('whatsapp-templates', \App\Http\Controllers\Admin\WhatsAppTemplateController::class);
        Route::post('whatsapp-templates/{whatsappTemplate}/toggle-status', [\App\Http\Controllers\Admin\WhatsAppTemplateController::class, 'toggleStatus'])->name('whatsapp-templates.toggle-status');
        
        // About Sections Routes
        Route::get('about-sections', [\App\Http\Controllers\Admin\AboutSectionController::class, 'index'])->name('about-sections.index');
        Route::post('about-sections/update-order', [\App\Http\Controllers\Admin\AboutSectionController::class, 'updateOrder'])->name('about-sections.update-order');
        Route::post('about-sections/{section}/toggle-status', [\App\Http\Controllers\Admin\AboutSectionController::class, 'toggleStatus'])->name('about-sections.toggle-status');
        Route::post('about-sections/update-title', [\App\Http\Controllers\Admin\AboutSectionController::class, 'updateTitle'])->name('about-sections.update-title');
        
        // Short URL Routes
        Route::resource('short-urls', \App\Http\Controllers\Admin\ShortUrlController::class);
        
    });
});
