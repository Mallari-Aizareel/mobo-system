<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TrainingCenterController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Tesda\TesdaAccountSettingsController;
use App\Http\Controllers\Admin\AdminEnrolledTraineeController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\EnrolledTraineeController;
use App\Http\Controllers\Admin\AdminAgencyController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Agency\JobPostController;
use App\Http\Controllers\Agency\AgencyProfileController;
use App\Http\Controllers\Tesda\TesdaProfileController;
use App\Http\Controllers\Tesda\TesdaResumeController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Tesda\TesdaDashboardController;
use App\Http\Controllers\Tesda\TesdaHomeController;
use App\Http\Controllers\Agency\AgencyDashboardController;
use App\Http\Controllers\Agency\JobInteractionController; 
use App\Http\Controllers\Agency\MessageController; 
use App\Http\Controllers\Tesda\TesdaMessageController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Tesda\TesdaNotificationController;
use App\Http\Controllers\Agency\AgencyFeedbackController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/portal/login', [PortalAuthController::class, 'showLoginForm'])
    ->name('portal.login');
Route::post('/portal/login', [PortalAuthController::class, 'login']);
Route::post('/portal/logout', [PortalAuthController::class, 'logout'])
    ->name('portal.logout');

Route::get('/portal/register', [PortalAuthController::class, 'showRegisterForm'])
    ->name('portal.register');
Route::post('/portal/register/step1', [PortalAuthController::class, 'registerStep1'])
    ->name('portal.register.step1');
Route::get('/portal/register/agency-account', [PortalAuthController::class, 'showAgencyAccountForm'])
    ->name('portal.agency.register.account');

Route::get('/portal/register/account', [PortalAuthController::class, 'showAccountForm'])
    ->name('portal.register.account');
Route::post('/portal/register/step2', [PortalAuthController::class, 'storeStep2'])
    ->name('portal.register.step2');
Route::post('/portal/register/agency-account', [PortalAuthController::class, 'agencyStoreStep2'])
    ->name('portal.agency.register.step2');

Route::get('/portal/register/final', [PortalAuthController::class, 'showFinalStep'])
    ->name('portal.register.final');
Route::post('/portal/register/final', [PortalAuthController::class, 'registerFinal'])
    ->name('portal.register.final.submit');
Route::get('/portal/register/agency-final', [PortalAuthController::class, 'showAgencyFinalStep'])
    ->name('portal.agency.register.final');
Route::post('/portal/register/agency-final', [PortalAuthController::class, 'registerAgencyFinal'])
    ->name('portal.agency.register.final.submit');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/account-settings', [AccountSettingsController::class, 'edit'])
        ->name('account.edit');
    Route::put('/account-settings', [AccountSettingsController::class, 'update'])
        ->name('account.update');
    Route::post('/account-settings/upload', [AccountSettingsController::class, 'upload'])
        ->name('account-settings.upload');

    Route::resource('training-centers', TrainingCenterController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::resource('courses', CourseController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/manage-enrolled-trainees', [AdminEnrolledTraineeController::class, 'index'])
        ->name('trainees.manage');
    Route::get('/tesda-graduates', [AdminEnrolledTraineeController::class, 'graduates'])
        ->name('tesda.graduates');
    Route::get('/drafted-trainees', [AdminEnrolledTraineeController::class, 'showFailed'])
        ->name('trainees.failed');

    Route::get('/agencies', [AdminAgencyController::class, 'index'])
        ->name('agencies.index');

    Route::patch('/trainees/{trainee}/status', [AdminEnrolledTraineeController::class, 'updateStatus'])
        ->name('trainees.status');

    Route::get('/agreements', [AgreementController::class, 'index'])
        ->name('agreements.index');
    Route::post('/agreements', [AgreementController::class, 'store'])
        ->name('agreements.store');

   Route::get('/classes', [RoomController::class, 'index'])
        ->name('rooms.index');
    Route::post('/classes', [RoomController::class, 'store'])
        ->name('rooms.store');
    Route::get('/classes/list', [RoomController::class, 'classesList'])
        ->name('classes.list');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])
        ->name('rooms.show');

    Route::get('faqs', [FaqController::class, 'index'])
        ->name('faqs.index');
    Route::post('faqs', [FaqController::class, 'store'])
        ->name('faqs.store');
    Route::put('faqs/{faq}', [FaqController::class, 'update'])
        ->name('faqs.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])
        ->name('faqs.destroy');

    Route::get('messages', [AdminMessageController::class, 'index'])
        ->name('messages-index');
    Route::post('messages', [AdminMessageController::class, 'store'])
        ->name('messages-store');


});

Route::prefix('tesda')->name('tesda.')->middleware(['auth'])->group(function () {
    Route::get('/home', [TesdaHomeController::class, 'index'])
        ->name('home');

    Route::get('/dashboard', [TesdaDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/class/{id}', [TesdaDashboardController::class, 'show'])
        ->name('class.show');
    Route::get('/faqs', [TesdaDashboardController::class, 'faqs'])
        ->name('tesda.faqs');
    
    Route::get('/account-settings', [TesdaAccountSettingsController::class, 'index'])
        ->name('account.settings');
    Route::put('/account-settings', [TesdaAccountSettingsController::class, 'update'])
        ->name('account.settings.update');

    Route::post('/skills', [TesdaAccountSettingsController::class, 'storeSkill'])
        ->name('skills.store');
    Route::put('/skills/{skill}', [TesdaAccountSettingsController::class, 'update'])
        ->name('skills.update');
    Route::delete('/skills/{skill}', [TesdaAccountSettingsController::class, 'destroySkill'])
        ->name('skills.destroy');

    Route::get('/enroll-courses', [EnrolledTraineeController::class, 'index'])
        ->name('enroll');
    Route::post('/enroll-courses', [EnrolledTraineeController::class, 'store'])
        ->name('enroll.submit');

    Route::get('/profile', [TesdaProfileController::class, 'index'])
        ->name('profile');
    Route::get('/edit-info', [TesdaProfileController::class, 'edit'])
        ->name('edit');
    Route::get('/resume', [TesdaResumeController::class, 'index'])
        ->name('resume');

    Route::get('/create-resume', [TesdaResumeController::class, 'create'])
        ->name('resume.create');
    Route::post('/create-resume', [TesdaResumeController::class, 'store'])
        ->name('resume.store');

    // Auto-apply route
    Route::post('/auto/{id}', [JobInteractionController::class, 'apply'])
        ->name('apply');


    Route::get('Inboxes', [TesdaMessageController::class, 'index'])
        ->name('messages-index');
    Route::post('messages', [TesdaMessageController::class, 'store'])
        ->name('messages-store');
    Route::put('messages/{id}', [TesdaMessageController::class, 'update'])
        ->name('messages-update');
    Route::delete('messages/{id}', [TesdaMessageController::class, 'destroy'])
        ->name('messages-destroy');

    Route::get('notifications', [TesdaNotificationController::class, 'index'])
        ->name('notifications');
    Route::get('notifications/read/{id}', [TesdaNotificationController::class, 'markAsRead'])
        ->name('notifications-read');
});


Route::prefix('agency')->name('agency.')->middleware(['auth'])->group(function () {
    Route::get('/home', [JobPostController::class, 'index'])
        ->name('job-posts.index');

    Route::post('/job-posts', [JobPostController::class, 'store'])
        ->name('job-posts.store');

    Route::get('/dashboard', [AgencyDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('faqs', [AgencyDashboardController::class, 'faqs'])
        ->name('faqs');

    Route::get('/profile', [AgencyProfileController::class, 'index'])
        ->name('profile');

    Route::get('/edit-info', [AgencyProfileController::class, 'edit'])
        ->name('edit-info');
    Route::put('/update-info', [AgencyProfileController::class, 'update'])
        ->name('update-info');
        
    Route::get('/manage', [JobPostController::class, 'manage'])
        ->name('job-posts.manage');
    Route::put('/{id}', [JobPostController::class, 'update'])
        ->name('job-posts.update');
    Route::delete('/{id}', [JobPostController::class, 'destroy'])
        ->name('job-posts.destroy');
    Route::get('/manage-posts', [JobPostController::class, 'manage'])
        ->name('manage-posts');
    
    Route::get('/profile/{id}', [AgencyProfileController::class, 'show'])
        ->name('show');

    Route::post('/like/{id}', [JobInteractionController::class, 'like'])
        ->name('like');
    Route::post('/comment{id}', [JobInteractionController::class, 'comment'])
        ->name('comment');
    Route::delete('/comments/{id}', [JobPostController::class, 'deleteComment'])
        ->name('destroy');
    
    Route::get('messages', [MessageController::class, 'index'])
        ->name('messages-index');
    Route::post('/messages', [MessageController::class, 'store'])
        ->name('messages-store');
    Route::put('/messages/{id}', [MessageController::class, 'update'])
        ->name('messages-update'); // Update
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])
        ->name('messages-destroy'); // Delete

    Route::post('/agency/{agency}/like', [AgencyFeedbackController::class, 'like'])
    ->name('like');
    Route::post('/agency/{agency}/rate', [AgencyFeedbackController::class, 'rate'])
    ->name('rate');


});