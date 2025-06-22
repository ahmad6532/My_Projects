<?php

use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\HeadOfficeRequestController;
use App\Http\Controllers\Admin\HeadOfficesController;
use App\Http\Controllers\Admin\NationalAlertsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HeadOffice\CaseManagerController;
use App\Http\Controllers\HeadOffice\HeadOfficeBrandUpdateRequestsController;
use App\Http\Controllers\HeadOffice\HeadOfficeController;
use App\Http\Controllers\HeadOffice\HeadOfficeDetailUpdateRequestsController;
use App\Http\Controllers\HeadOffice\HeadOfficeUsersController;
use App\Http\Controllers\HeadOffice\NearMissManagerController;
use App\Http\Controllers\Location\Forms\BeSpokeFormsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Location\LocationDetailUpdateRequestsController;
use App\Http\Controllers\Location\LocationPasswordUpdateRequestsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Location\LocationBrandUpdateRequestsController;
use App\Http\Controllers\Admin\LocationsController;
use App\Http\Controllers\Admin\ServiceMessagesController;
use App\Http\Controllers\Api\Auth\LocationSignupController;
use App\Http\Controllers\Api\Auth\UsersSignupController;
use App\Http\Controllers\BeSpokeFormCategoryController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DefaultDocumentController;
use App\Http\Controllers\GdprController;
use App\Http\Controllers\HeadOffice\HeadOfficeOrganizationController;
use App\Http\Controllers\HeadOffice\LocationsController as HeadOfficeLocationsController;
use App\Http\Controllers\HeadOffice\OrganisationSettingController;
use App\Http\Controllers\HeadOffice\PatientSafetyAlertsController;
use App\Http\Controllers\SharedCaseApprovedEmailController;
use App\Models\BeSpokeFormCategory;
use App\Models\DefaultDocument;
use App\Models\SharedCaseApprovedEmail;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\webPagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Temp routes only valid during debug //
if (env('APP_DEBUG') == 'true') {
    Route::get('/migrate', function () {
        return Artisan::call('migrate');
    });
}

Route::post('location/register', [LocationSignupController::class, 'register'])
    ->name('api.locations.location.register');
Route::get('/remove/document/{id}', [DocumentController::class, 'remove_item'])->name('view.remove_item');




Route::get('/', [webPagesController::class, 'home_page'])->name('home');
Route::get('/book-session', [webPagesController::class, 'book_session_page'])->name('book_session');
Route::get('/support', [webPagesController::class, 'support_page'])->name('support');
Route::get('/pharmacy', [webPagesController::class, 'pharmacy_page'])->name('pharmacy');
Route::get('/confirm', [webPagesController::class, 'confirm_page'])->name('confirm_page');
Route::post('/get_in_touch', [webPagesController::class, 'send_in_touch_mail'])->name('home.get_in_touch');
Route::post('/about-us/job-apply', [webPagesController::class, 'job_apply'])->name('about-us.job-apply');
Route::get('/about-us', function () {
    return view('about_us');
})->name('about_us');

Route::get('/both_found', function () {
    return view('head_office.both_found');
});
Route::get('/csrf_token', function () {
    return csrf_token();
});
Route::get('create/head_office/user/{id}', [HomeController::class, 'create_head_office_user'])->name('create_head_office_user');  // need changing it after wards 
Route::get('create/head_office_request/user/{id}', [HomeController::class, 'create_head_office_user_request'])->name('create.headOfficeUser.request');
Route::get('/email/verify/{type}/{token}', [HomeController::class, 'verify_email'])->name('verification.verify');
Route::get('/redirect_to', [HomeController::class, 'get_redirect_to'])->name('app.redirect_to');


Route::get('head_office/external/get_form_json/{id}', [BeSpokeFormsController::class, 'getFormJson'])->name('external.get_form_json');
Route::post('head_office/external/submit_form/{id}', [BeSpokeFormsController::class, 'submitLocationFormJson'])->name('external.form.submit');
Route::post('be_spoke_form/external_link/record/save', [BeSpokeFormsController::class, 'saveRecord'])->name('external_link.be_spoke_forms.be_spoke_form.save');


/// Location user logged in with or without User login
Route::middleware(['auth:location', 'email_verified', 'account_suspended', 'otp_checker'])->group(function () {
    Route::get('/location/user_login', [LocationController::class, 'user_login_view'])->name('location.user_login_view');
    Route::get('/change-password', [LocationController::class, 'showChangePasswordForm'])->name('locations.changePasswordForm');
    Route::post('/change-password', [LocationController::class, 'changePassword'])->name('locations.changePassword');

    Route::post('/location/pin_login', [LoginController::class, 'pinlogin'])->name('location.pinlogin');
    Route::post('/location/pin_login2', [LoginController::class, 'pinlogin2'])->name('location.pinlogin2');
    Route::get('/location/color_css.css', [LocationController::class, 'color_css'])->name('location.color_css');
    /// Custom Dynamic CSS //
});

Route::middleware(['email_verified', 'account_suspended', 'user_login_session', 'otp_checker', 'activity_log'])->group(function () {
    Route::get('/user/hide_email/{type}', [UserController::class, 'hide_email'])->name('user.hide.email');
    Route::get('/user/hide_phone/{type}', [UserController::class, 'hide_phone'])->name('user.hide.phone');
    Route::post('/user/update_picture', [UserController::class, 'update_picture'])->name('user.update_picture');
});

//// Standard user Logged in with or without Location Login
Route::middleware(['auth:user', 'email_verified', 'account_suspended', 'user_login_session', 'otp_checker', 'activity_log'])->group(function () {
    Route::get('/user/feedback/{id?}', [UserController::class, 'user_feedback'])->name('user.feedback');
    Route::get('/user/feedback_seen/{id}', [UserController::class, 'user_feedback_seen'])->name('user.feedback_seen');
    Route::get('/user/company', [UserController::class, 'user_company'])->name('user.company');
    Route::get('/user/view_profile', [UserController::class, 'view_profile'])->name('user.view_profile');
    Route::get('/user/requests', [UserController::class, 'requests'])->name('user.requests');
    Route::get('/user/request/view/{id}', [UserController::class, 'view_request'])->name('user.request.view');
    Route::get('/user/request/save_answer', [UserController::class, 'save_answer'])->name('user.save_answer');
    Route::post('/user/request/submit_request', [UserController::class, 'submit_request'])->name('user.submit.request');
    Route::get('/user/sharedcases', [UserController::class, 'shared_cases'])->name('user.shared_cases');
    Route::get('/user/statement', [UserController::class, 'statement'])->name('user.statement');
    Route::post('/user/update_first_name', [UserController::class, 'update_first_name'])->name('user.update.first_name');
    Route::post('/user/update_sur_name', [UserController::class, 'update_sur_name'])->name('user.update.sur_name');
    Route::post('/user/update_phone', [UserController::class, 'update_phone'])->name('user.update.phone');
    Route::post('/user/update_email', [UserController::class, 'update_email'])->name('user.update.email');
    Route::post('/user/create_contact', [UserController::class, 'create_contact'])->name('user.create.contact');
    Route::get('/user/delete_contact/{id}', [UserController::class, 'delete_contact'])->name('user.delete.contact');
    Route::get('/user/activity', [UserController::class, 'activity'])->name('user.activity');
    Route::get('/user/statement/{id}', [UserController::class, 'single_statement'])->name('user.statement.single_statement');
    Route::post('/user/single_statement_update/{id}/{type}', [UserController::class, 'single_statement_update'])->name('user.statement.single_statement_update');
    Route::post('/user/add_phone', [UserController::class, 'add_phone'])->name('user.add.phone');
    Route::post('/user/add_email', [UserController::class, 'add_email'])->name('user.add.email');

    Route::post('/user/update_password', [UserController::class, 'update_password'])->name('user.update_password');
    Route::post('/user/document/upload/hashed', [DocumentController::class, 'uploadHashed'])->name('user.document.uploadHashed');

    Route::get('/user/view/patient/safety/alert/document/{id}', [DocumentController::class, 'view'])->name('user.view.attachment');
    Route::get('/user/share_case/{id}', [UserController::class, 'shared_case'])->name('user.share_case');
    Route::post('/user/change_password', [UserController::class, 'change_password'])->name('user.change_password');
    Route::get('/user/share_case/remove/{id}', [UserController::class, 'shared_case_remove'])->name('user.share_case.remove');
    Route::post('/user/share_case/{id}/request_extension', [UserController::class, 'request_extension'])->name('user.share_case.request_extension');
    Route::get('/user/share_case/{id}/request_extension/{extionsion_id}', [UserController::class, 'request_extension_remove'])->name('user.share_case.request_extension_remove');
    Route::get('/user/share_case/{id}/cancel_extension/{extionsion_id}', [UserController::class, 'cancel_extension'])->name('user.share_case.cancel_extension');
    Route::post('/user/share_case/{id}/comment', [UserController::class, 'share_case_comment'])->name('user.share_case.share_case_comment');
    Route::get('user/share_case/unseen_comment/{id}', [UserController::class, 'unseen_comment'])->name('user.share_case.unseen_comment');
    Route::get('user/share_case/seen_comment/{comment_id}', [UserController::class, 'seen_comment'])->name('user.share_case.seen_comment');
    Route::get('user/share_case/comment/delete/{comment_id?}', [UserController::class, 'delete_comment'])->name('user.share_case.delete_comment');
    Route::get('/user/draft', [UserController::class, 'user_draft'])->name('user.draft');
    Route::get('/otp/security', [UserController::class, 'otp_security'])->name('otp.security');

});


Route::get('/remove_pin/{id}', [LocationController::class, 'remove_pin'])->name('location.remove_pin')->middleware(['auth:location']);
Route::get('/pinned_user/{id}', [LocationController::class, 'pinned_user'])->name('location.pinned_user')->middleware(['auth:location']);

/// Standard User and Location User simultaneous Login
Route::middleware(['auth:user', 'auth:location', 'email_verified', 'account_suspended', 'user_login_session', 'location_access', 'activity_log'])->group(function () {

    /// Location Routes ///
    Route::group(['prefix' => 'location',], function () {
        Route::post('/bespokeforms/submit_form_edit/{id}', [BeSpokeFormsController::class, 'submitLocationFormJson_edit'])->name('location.be_spoke_forms.location_submit_form_json_edit');




        Route::post('/document/upload/hashed', [DocumentController::class, 'uploadHashed'])->name('location.document.uploadHashed');
        Route::post('/document/remove/hashed', [DocumentController::class, 'removedHashed'])->name('location.document.removedHashed');

        Route::get('/dashboard', [LocationController::class, 'dashboard'])->name('location.dashboard');



        Route::get('/edit_location_details', [LocationController::class, 'edit_location_details'])->name('location.edit_location_details');
        Route::post('/update_location_details', [LocationDetailUpdateRequestsController::class, 'update_location_details'])->name('location.request_update_details');
        Route::post('/manager/update_location_details', [LocationDetailUpdateRequestsController::class, 'manager_update_location_details'])->name('location.manager_update_details');

        Route::post('/settings/update_opening_hours', [LocationDetailUpdateRequestsController::class, 'update_opening_hours'])->name('location.update_opening_hours');

        Route::get('/change_password', [LocationController::class, 'update_password_view'])->name('location.update_password_view');
        Route::post('/update_password', [LocationPasswordUpdateRequestsController::class, 'update_password'])->name('location.request_update_password');


        Route::get('/verified_devices', [LocationController::class, 'verified_devices'])->name('location.verified_devices');
        Route::get('/end_user_session/{id}', [LocationController::class, 'end_user_session'])->name('location.end_user_session');

        Route::get('/subscription', [LocationController::class, 'subscription'])->name('location.subscription');
        Route::get('/blocked_users', [LocationController::class, 'blocked_users'])->name('location.blocked_users');
        Route::get('/export_incidents', [LocationController::class, 'export_incidents'])->name('location.export_incidents');

        Route::get('/color_branding', [LocationController::class, 'color_branding'])->name('location.color_branding');
        Route::post('/update_location_branding', [LocationBrandUpdateRequestsController::class, 'update_location_branding'])->name('location.update_location_branding');
        Route::post('/manager/update_location_branding', [LocationBrandUpdateRequestsController::class, 'manager_update_location_branding'])->name('location.manager_update_location_branding');


        Route::get('/reporting', [LocationController::class, 'reporting'])->name('location.reporting');


        Route::get('/create_pin', [LocationController::class, 'create_pin'])->name('location.create_pin');
        Route::post('/update_pin', [LocationController::class, 'update_pin'])->name('location.update_pin');




        Route::get('/view_near_miss', [LocationController::class, 'view_near_miss'])->name('location.view_near_miss');
        Route::get('/view_drafts', [LocationController::class, 'view_drafts'])->name('location.view_drafts');
        Route::get('/view_drafts/delete-draft/{id}', [LocationController::class, 'delete_drafts'])->name('location.delete_drafts');
        Route::get('/near/miss/analysis', [LocationController::class, 'nearMissAnalysis'])->name('location.nearmiss.analysis');
        Route::any('/near/miss/delete/{id?}', [LocationController::class, 'delete'])->name('location.near_miss.delete');
        Route::any('/near/miss/delete_near/{id?}', [LocationController::class, 'deleteNearMiss'])->name('location.near_miss.delete_near');

        Route::get('/view_dispensing_incidents', [LocationController::class, 'view_dispensing_incidents'])->name('location.view_dispensing_incidents');
        Route::get('/view_patient_safety_alerts', [LocationController::class, 'view_patient_safety_alerts'])->name('location.view_patient_safety_alerts');
        Route::get('/view/patient/safety/alert/{id}', [LocationController::class, 'view_patient_safety_alert'])->name('location.view_patient_safety_alert');

        Route::get('/view/patient/safety/alert/remove/action/{id}', [LocationController::class, 'remove_action_patient_safety_alert'])->name('location.remove_action_patient_safety_alert');
        Route::post('/view/patient/safety/alert/comment/{id}', [LocationController::class, 'patient_safety_alert_add_comment'])->name('location.patient_safety_alert_add_comment');
        Route::get('/view/patient/safety/alert/comment/delete/{id}', [LocationController::class, 'patient_safety_alert_delete_comment'])->name('location.patient_safety_alert_delete_comment');
        Route::get('/view/patient/safety/alert/document/{id}', [DocumentController::class, 'view'])->name('location.view.attachment');


        Route::get('/view/notifications', [LocationController::class, 'view_notifications'])->name('location.view_notifications');
        Route::get('/process/notification/url/{id}', [LocationController::class, 'process_notifcation_url'])->name('location.process_notifcation_url');
        Route::post('/safety/alert/action/save/{id}', [LocationController::class, 'save_safety_alert_action'])->name('location.patient_safety_alert_action.save');

        Route::any('/settings/nearmisses', [LocationController::class, 'settingsNearMisses'])->name('location.settings.nearmisses');
        Route::any('/nearmiss/notification/test', [LocationController::class, 'testEmailNotificationDaily'])->name('location.settings.test');
        Route::get('/mark/read/all/notifications', [LocationController::class, 'mark_read_all_notifications'])->name('location.mark_read_all_notifications');

        Route::group(['prefix' => 'category'], function () {
            Route::get('/', [BeSpokeFormCategoryController::class, 'index'])->name('location.be_spoke_form_category.index');

            Route::get('/create/{id?}', [BeSpokeFormCategoryController::class, 'create'])->name('location.be_spoke_form_category.create');

            Route::post('/store/{id?}', [BeSpokeFormCategoryController::class, 'store'])->name('location.be_spoke_form_category.store');
            Route::delete('/{id}', [BeSpokeFormCategoryController::class, 'destroy'])->name('location.be_spoke_form_category.delete');

        });
        route::post('five_whys/quesiton', [BeSpokeFormsController::class, 'store_question_answer'])->name('route_cause_analysis.request.store_question_answer');


        Route::group(['prefix' => 'bespokeforms',], function () {







        

            # Save record
            Route::post('/record/save/mod/{id?}', [BeSpokeFormsController::class, 'saveModifications'])
                ->name('be_spoke_forms.be_spoke_form.mod.save');
            Route::post('/record/update/{id}', [BeSpokeFormsController::class, 'saveUpdate'])
                ->name('be_spoke_forms.be_spoke_form.update');
            Route::get('/record/view/attachment/{id}', [DocumentController::class, 'view'])
                ->name('be_spoke_forms.record.update.view.attachment');


            




            Route::any('/records/{form_id?}', [BeSpokeFormsController::class, 'records'])
                ->name('be_spoke_forms.be_spoke_form.records');
            Route::get('/records_view/{form_id?}', [BeSpokeFormsController::class, 'record_detail_view'])
                ->name('be_spoke_forms.be_spoke_form.records_view');

            Route::any('/record/preview/{record_id}', [BeSpokeFormsController::class, 'recordPreview'])
                ->name('be_spoke_forms.be_spoke_form.record.preview');

            Route::any('/root_cause_analysis{id}', [BeSpokeFormsController::class, 'root_cause_analysis'])
                ->name('be_spoke_forms.be_spoke_form.record.root_cause_analysis');

            Route::any('/display/information/{condition_id}', [BeSpokeFormsController::class, 'displayInformation'])
                ->name('be_spoke_forms.be_spoke_form.display.information');


            Route::any('/record/{record_id}/request/{type}', [BeSpokeFormsController::class, 'root_cause_analysis_request'])
                ->name('be_spoke_forms.be_spoke_form.root_cause_analysis_request');


            Route::any('/dmd/process', [BeSpokeFormsController::class, 'SaveDmdsToDatabase'])
                ->name('be_spoke_forms.be_spoke_form.dmd.process');
            route::post('default_task/save/', [BeSpokeFormsController::class, 'default_task_save'])->name('be_spoke_form.default_task_save');

            /// Routes for Bespoke Form v3 (For Location Only !) ///
            Route::get('/get_draft_form_json/{id}', [BeSpokeFormsController::class, 'getDraftFormJson'])->name('head_office.be_spoke_forms_templates.get_draft_form_json');
            Route::post('/submit_draft_form/{id}', [BeSpokeFormsController::class, 'submitLocationDraftFormJson'])->name('location.be_spoke_forms.location_submit_draft_form_json');
            Route::get('form_limit_checks/{formId}', [BeSpokeFormsController::class, 'CheckFormLImits'])->name('location.form.check_form_limits');




        });
        Route::post('root_cause_analysis_save/{id}', [BeSpokeFormsController::class, 'root_cause_analysis_save'])
            ->name('root_cause_analysis_save');
        Route::post('root_cause_analysis_answer_delete', [BeSpokeFormsController::class, 'root_cause_analysis_answer_delete'])
            ->name('root_cause_analysis_answer_delete');

        route::get('root_cause_analysis/requests', [BeSpokeFormsController::class, 'root_cause_analysis_requests'])->name('location.root_cause_analysis.requests');
        route::get('root_cause_analysis/fish_bone/{id}', [BeSpokeFormsController::class, 'fish_bone'])->name('location.root_cause_analysis.fish_bone');
        route::get('root_cause_analysis/five_whys/{id}', [BeSpokeFormsController::class, 'five_whys'])->name('location.root_cause_analysis.five_whys');

    });

    /// User Routes /// Any user specific Tasks that must required Location logged in 

});

Route::get('/location/bespokeforms/get_form_json/{id}', [BeSpokeFormsController::class, 'getLocationFormJson'])->name('location.be_spoke_form.get_form_json');
Route::post('/location/bespokeforms/submit_form/{id}', [BeSpokeFormsController::class, 'submitLocationFormJson'])->name('location.be_spoke_forms.location_submit_form_json');

Route::middleware(['auth:location', 'email_verified', 'account_suspended'])->group(function () {
    Route::get('/report_near_miss/{id?}', [LocationController::class, 'near_miss'])->name('location.near_miss');
    Route::post('/near_miss_save/', [LocationController::class, 'nearMissSave'])->name('location.near_miss.save');
    Route::get('/near_miss/saved', [LocationController::class, 'nearMissSaved'])->name('location.near_miss.saved');

    Route::get('/near/miss/qr', [LocationController::class, 'nearMissQrCode'])->name('location.near_miss.qr_code');

    Route::get('/report_dispensing_incidents', [LocationController::class, 'dispensing_incidents'])->name('location.dispensing_incidents');
});



Route::any('/forms/attachment/upload', [BeSpokeFormsController::class, 'uploadGeneralAttachment'])
    ->name('be_spoke_forms_templates.attachment.upload');

Route::any('/forms/attachments/{filename}', [BeSpokeFormsController::class, 'displayGeneralAttachment'])
    ->name('be_spoke_forms_templates.attachment.display');




//// Admin Login ////
Route::middleware(['auth:admin'])->group(function () {

    /// Admin Routes ///
    Route::group(['prefix' => 'admin',], function () {

        Route::get('/dashboard', [AdminsController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/edit_my_details', [AdminsController::class, 'edit_my_details'])->name('admin.edit_my_details');
        Route::get('/update_password', [AdminsController::class, 'update_password'])->name('admin.update_password');
        Route::get('/logout', function () {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        })->name('admin.logout');

        Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');
        Route::post('/database', [DatabaseController::class, 'store'])->name('database.store');
        Route::get('/head_office/login/{id}', [HeadOfficesController::class, 'head_office_login'])->name('admin.headoffice.login');
        Route::get('/location/login/{id}', [LocationsController::class, 'location_login'])->name('admin.location.login');



        Route::group(['prefix' => 'locations',], function () {
            Route::get('/', [LocationsController::class, 'index'])
                ->name('locations.location.index');
            Route::get('/create', [LocationsController::class, 'create'])
                ->name('locations.location.create');
            Route::get('/show/{location}', [LocationsController::class, 'show'])
                ->name('locations.location.show')->where('id', '[0-9]+');
            Route::get('/{location}/edit', [LocationsController::class, 'edit'])
                ->name('locations.location.edit')->where('id', '[0-9]+');
            Route::post('/', [LocationsController::class, 'store'])
                ->name('locations.location.store');
            Route::put('location/{location}', [LocationsController::class, 'update'])
                ->name('locations.location.update')->where('id', '[0-9]+');
            Route::delete('/location/{location}', [LocationsController::class, 'destroy'])
                ->name('locations.location.destroy')->where('id', '[0-9]+');


            Route::get('/manager/{location}/assign', [LocationsController::class, 'assign_manager_view'])
                ->name('locations.location.assign_manager_view');
            Route::post('/manager/{location}/assign', [LocationsController::class, 'assign_manager'])
                ->name('locations.location.assign_manager');
            Route::get('/manager/remove/{manager_id}', [LocationsController::class, 'assign_manager_remove'])
                ->name('locations.location.assign_manager_remove');

            Route::get('/head/office/{location}/assign', [LocationsController::class, 'view_assign_head_office'])
                ->name('admins.location.view_assign_ho');
            Route::post('/head/office/{location}/save', [LocationsController::class, 'save_assign_head_office'])
                ->name('admins.location.save_assign_ho');

            Route::get('/head/office/remove/{location_id}', [LocationsController::class, 'remove_head_office'])
                ->name('admin.location.remove_head_office');



            Route::get('/active/{location}', [LocationsController::class, 'toggle_active'])
                ->name('locations.location.toggle_active')->where('id', '[0-9]+');

            Route::get('/activation_email/{location}', [LocationsController::class, 'activation_email'])
                ->name('locations.location.activation_email')->where('id', '[0-9]+');

            Route::get('/archived/{location}', [LocationsController::class, 'toggle_archived'])
                ->name('locations.location.toggle_archived')->where('id', '[0-9]+');

            Route::get('/suspend/{location}', [LocationsController::class, 'toggle_suspend'])
                ->name('locations.location.toggle_suspend')->where('id', '[0-9]+');


        });


        Route::group([
            'prefix' => 'users',
        ], function () {
            Route::get('/', [UsersController::class, 'index'])
                ->name('users.user.index');
            Route::get('/create', [UsersController::class, 'create'])
                ->name('users.user.create');
            Route::get('/show/{user}', [UsersController::class, 'show'])
                ->name('users.user.show')->where('id', '[0-9]+');
            Route::get('/{user}/edit', [UsersController::class, 'edit'])
                ->name('users.user.edit')->where('id', '[0-9]+');
            Route::post('/', [UsersController::class, 'store'])
                ->name('users.user.store');
            Route::put('user/{user}', [UsersController::class, 'update'])
                ->name('users.user.update')->where('id', '[0-9]+');
            Route::delete('/user/{user}', [UsersController::class, 'destroy'])
                ->name('users.user.destroy')->where('id', '[0-9]+');

            Route::get('/active/{user}', [UsersController::class, 'toggle_active'])
                ->name('users.user.toggle_active')->where('id', '[0-9]+');

            Route::get('/activation_email/{user}', [UsersController::class, 'activation_email'])
                ->name('users.user.activation_email')->where('id', '[0-9]+');

            Route::get('/archived/{user}', [UsersController::class, 'toggle_archived'])
                ->name('users.user.toggle_archived')->where('id', '[0-9]+');

            Route::get('/suspend/{user}', [UsersController::class, 'toggle_suspend'])
                ->name('users.user.toggle_suspend')->where('id', '[0-9]+');

        });

        Route::group([
            'prefix' => 'head_offices',
        ], function () {
            Route::get('/', [HeadOfficesController::class, 'index'])
                ->name('head_offices.head_office.index');
            Route::get('/create', [HeadOfficesController::class, 'create'])
                ->name('head_offices.head_office.create');
            Route::get('/show/{headOffice}', [HeadOfficesController::class, 'show'])
                ->name('head_offices.head_office.show')->where('id', '[0-9]+');
            Route::get('/{headOffice}/edit', [HeadOfficesController::class, 'edit'])
                ->name('head_offices.head_office.edit')->where('id', '[0-9]+');
            Route::post('/', [HeadOfficesController::class, 'store'])
                ->name('head_offices.head_office.store');
            Route::put('head_office/{headOffice}', [HeadOfficesController::class, 'update'])
                ->name('head_offices.head_office.update')->where('id', '[0-9]+');
            Route::delete('/head_office/{headOffice}', [HeadOfficesController::class, 'destroy'])
                ->name('head_offices.head_office.destroy')->where('id', '[0-9]+');

            Route::get('/super_admin/{headOffice}/assign', [HeadOfficesController::class, 'assign_super_admin_view'])
                ->name('head_offices.head_office.assign_super_admin_view');
            Route::post('/super_admin/{headOffice}/assign', [HeadOfficesController::class, 'assign_super_admin'])
                ->name('head_offices.head_office.assign_super_admin');
            Route::get('/super_admin/{headOffice}/remove', [HeadOfficesController::class, 'assign_super_admin_remove'])
                ->name('head_offices.head_office.assign_super_admin_remove');


            Route::get('/archived/{headOffice}', [HeadOfficesController::class, 'toggle_archived'])
                ->name('head_offices.head_office.toggle_archived')->where('id', '[0-9]+');

            Route::get('/suspend/{headOffice}', [HeadOfficesController::class, 'toggle_suspend'])
                ->name('head_offices.head_office.toggle_suspend')->where('id', '[0-9]+');

            Route::group([
                'prefix' => '/requests',
            ], function () {

                Route::get('/', [HeadOfficeRequestController::class, 'index'])
                    ->name('head_office.request.index')->where('id', '[0-9]+');
                Route::get('/pending/{headOfficeRequest}', [HeadOfficeRequestController::class, 'request_pending'])
                    ->name('head_office.request.request_pending')->where('id', '[0-9]+');
                Route::get('/approved/{headOfficeRequest}', [HeadOfficeRequestController::class, 'request_approved'])
                    ->name('head_office.request.request_approved')->where('id', '[0-9]+');
                Route::get('/rejected/{headOfficeRequest}', [HeadOfficeRequestController::class, 'request_rejected'])
                    ->name('head_office.request.request_rejected')->where('id', '[0-9]+');
            });
        });


        Route::group([
            'prefix' => 'national_alerts',
        ], function () {
            Route::get('/', [NationalAlertsController::class, 'index'])->name('national_alerts.national_alert.index');
            Route::get('/create/{id?}', [NationalAlertsController::class, 'create'])
                ->name('national_alerts.national_alert.create');
            Route::get('/show/{nationalAlert}', [NationalAlertsController::class, 'show'])
                ->name('national_alerts.national_alert.show')->where('id', '[0-9]+');
            Route::post('/', [NationalAlertsController::class, 'store'])
                ->name('national_alerts.national_alert.store');
            Route::put('national_alert/{nationalAlert}', [NationalAlertsController::class, 'update'])
                ->name('national_alerts.national_alert.update')->where('id', '[0-9]+');
            Route::delete('/national_alert/{nationalAlert}', [NationalAlertsController::class, 'destroy'])
                ->name('national_alerts.national_alert.destroy')->where('id', '[0-9]+');


        });


        Route::group([
            'prefix' => 'service_messages',
        ], function () {
            Route::get('/', [ServiceMessagesController::class, 'index'])
                ->name('service_messages.service_message.index');
            Route::get('/create', [ServiceMessagesController::class, 'create'])
                ->name('service_messages.service_message.create');
            Route::get('/show/{serviceMessage}', [ServiceMessagesController::class, 'show'])
                ->name('service_messages.service_message.show')->where('id', '[0-9]+');
            Route::get('/{serviceMessage}/edit', [ServiceMessagesController::class, 'edit'])
                ->name('service_messages.service_message.edit')->where('id', '[0-9]+');
            Route::post('/', [ServiceMessagesController::class, 'store'])
                ->name('service_messages.service_message.store');
            Route::put('service_message/{serviceMessage}', [ServiceMessagesController::class, 'update'])
                ->name('service_messages.service_message.update')->where('id', '[0-9]+');
            Route::delete('/service_message/{serviceMessage}', [ServiceMessagesController::class, 'destroy'])
                ->name('service_messages.service_message.destroy')->where('id', '[0-9]+');

            Route::get('/{serviceMessage}/extend_duration', [ServiceMessagesController::class, 'extend_duration_view'])
                ->name('service_messages.service_message.extend_duration_view')->where('id', '[0-9]+');
            Route::post('/{serviceMessage}/extend_duration', [ServiceMessagesController::class, 'extend_duration'])
                ->name('service_messages.service_message.extend_duration')->where('id', '[0-9]+');

        });
    });



});




//// Head Office Login ////
Route::middleware(['auth:web', 'email_verified', 'account_suspended', 'user_login_session'])->group(function () {

    /// Head Office Routes ///
    Route::group(['prefix' => 'head_office',], function () {

        Route::get('/preview_list', [HeadOfficeController::class, 'preview_list'])->name('head_office.preview_list');
        Route::get('/select_head_office/{head_office_id}', [HeadOfficeController::class, 'select_head_office'])->name('head_office.select_head_office');
        //        Route::get('/admin_login', [HeadOfficeController::class, 'admin_login'] )->name('head_office.admin_login');
//        Route::post('/post_admin_login', [LoginController::class, 'head_office_admin_login'] )->name('head_office.post_admin_login');
    });
});




Route::post('/otp/security/all', [HeadOfficeController::class, 'otp_loc_all'])->name('otp.security.all');

Route::middleware(['auth:web', 'head_office_admin', 'email_verified', 'account_suspended', 'user_login_session', 'otp_checker', 'activity_log'])->group(function () {
    Route::post('/dismiss-toast', [HeadOfficeController::class, 'dismissToast'])->name('head_office.dismissToast')->middleware('throttle:5,1');

    /// Head Office Routes ///
    Route::group(['prefix' => 'head_office',], function () {

        Route::get('/user-profile',[HeadOfficeController::class, 'goto_user_profile'])->name('head_office.user.profile');

        Route::get('/otp-security/{id}', [HeadOfficeController::class, 'otp_loc'])->name('otp.loc.security');
        Route::get('/otp-security/email/{id}', [HeadOfficeController::class, 'otp_loc_email'])->name('otp.loc.email');
        Route::get('/otp-all/{id}', [HeadOfficeController::class, 'otp_loc'])->name('otp.loc.security');



        Route::get('/board/{id?}', [HeadOfficeController::class, 'board'])->name('head_office.board');
        Route::post('/near_miss/board', [HeadOfficeController::class, 'board_data_export'])->name('headOffice.board.near_miss_export');
        Route::post('/default_request_information_text/{id?}', [CaseManagerController::class, 'default_request_information_text'])->name('head_office.case.default_request_information_text');
        Route::get('/default_request_information_text/delete/{id?}', [CaseManagerController::class, 'default_request_information_text_delete'])->name('head_office.case.default_request_information_text.delete');





        Route::group(['prefix' => 'category', 'middleware' => ['check_permissions:forms']], function () {
            Route::get('/', [BeSpokeFormCategoryController::class, 'index'])->name('be_spoke_form_categories.be_spoke_form_category.index');

            Route::get('/create/{id?}', [BeSpokeFormCategoryController::class, 'create'])->name('be_spoke_form_categories.be_spoke_form_category.create');

            Route::post('/store/{id?}', [BeSpokeFormCategoryController::class, 'store'])->name('be_spoke_form_categories.be_spoke_form_category.store');
            Route::delete('/{id}', [BeSpokeFormCategoryController::class, 'destroy'])->name('be_spoke_form_categories.be_spoke_form_category.delete');

        });

        #be_spoke_forms
        Route::group(['prefix' => 'bespokeforms', 'middleware' => ['check_permissions:forms']], function () {
            Route::get('/head_office/bespokeforms/form_template/head_office/bespokeforms/stage_users/{stage_id}', [BeSpokeFormsController::class, 'stageUsers'])->name('head_office.be_spoke_forms_templates.stage-users');

            route::post('when_case_closed/{id}', [BeSpokeFormsController::class, 'when_case_closed'])->name('when_case_closed');
            route::post('case_must_review/{id}', [BeSpokeFormsController::class, 'case_must_review'])->name('case_must_review');
            route::get('rule_remove/{form_id}/{page_id}/{item_id}/{id}', [BeSpokeFormsController::class, 'rule_remove'])->name('head_office.be_spoke_form.rule_remove');
            route::get('rule_edit/{form_id}/{page_id}/{item_id}/{id}', [BeSpokeFormsController::class, 'rule_edit'])->name('head_office.be_spoke_form.rule_edit');
            route::post('form_card/save', [BeSpokeFormsController::class, 'form_card_save'])->name('head_office.be_spoke_form.form_card_save');
            route::get('form_card/delete/{id}', [BeSpokeFormsController::class, 'form_card_delete'])->name('head_office.be_spoke_form.form_card_delete');
            route::post('form_card/fields', [BeSpokeFormsController::class, 'form_card_fields'])->name('head_office.be_spoke_form.form_card_fields');
            route::post('is_allow_non_approved_emails_route', [BeSpokeFormsController::class, 'is_allow_non_approved_emails_route'])->name('head_office.case.is_allow_non_approved_emails_route');
            Route::post('/default_stage_save', [BeSpokeFormsController::class, 'default_stage_save'])->name('head_offie.form.default_stage_save');
            Route::post('/default_stage_delete', [BeSpokeFormsController::class, 'default_stage_delete'])->name('head_offie.form.default_stage_delete');


            Route::post('/swap_stage_route', [BeSpokeFormsController::class, 'swap_stage_route'])->name('head_offie.form.swap_stage_route');
            Route::post('/swap_task_route', [BeSpokeFormsController::class, 'swap_task_route'])->name('head_offie.form.swap_task_route');


            Route::get('/record/view/attachment/{id}', [DocumentController::class, 'view'])
                ->name('head_office.be_spoke_forms.record.update.view.attachment');
            Route::get('/record/view/new_attachment/{id}', [DocumentController::class, 'new_view'])
                ->name('head_office.be_spoke_forms.record.update.view.new_attachment');

            Route::get('/', [BeSpokeFormsController::class, 'index'])->name('head_office.be_spoke_form.index');
            // Route::get('/form_view/{id}', [BeSpokeFormsController::class, 'form_view'])->name('head_office.be_spoke_forms_templates.form_view');
            Route::get('/form_template/{id?}', [BeSpokeFormsController::class, 'formTemplate'])->name('head_office.be_spoke_forms_templates.form_template');
            Route::get('/form_template/calender/delete/{id?}', [BeSpokeFormsController::class, 'formEventDelete'])->name('form_template.calender.event_delete');
            Route::get('/form_template_duplicate/{id?}', [BeSpokeFormsController::class, 'formTemplateDuplicate'])->name('head_office.be_spoke_forms_templates.form_template_duplicate');
            Route::post('/form_template_duplicate_bulk', [BeSpokeFormsController::class, 'formTemplateDuplicateBulk'])->name('head_office.be_spoke_forms_templates.form_template_duplicate_bulk');

            // Route to edit Event In Bespoke Form
            Route::post('/route/to/save/event/{id}', [BeSpokeFormsController::class, 'updateEvent']);




            Route::any('/form_template/save/{id?}', [BeSpokeFormsController::class, 'formTemplateSave'])->name('head_office.be_spoke_forms_templates.form_template_save');
            
            Route::get('/restore/{id}', [BeSpokeFormsController::class, 'restoreForm'])->name('head_office.be_spoke_forms.be_spoke_form.restore');
            Route::get('/soft-delete/{id}', [BeSpokeFormsController::class, 'softDeleteForm'])->name('head_office.be_spoke_forms.be_spoke_form.soft_delete');

 
            
            Route::get('/delete/{id}', [BeSpokeFormsController::class, 'deleteForm'])->name('head_office.be_spoke_forms.be_spoke_form.delete');
            Route::any('/form_template/stages/save', [BeSpokeFormsController::class, 'formTemplateStagesSave'])->name('head_office.be_spoke_forms_templates.form_stages_save');
            Route::get('/preview/{id}', [BeSpokeFormsController::class, 'preview'])->name('head_office.be_spoke_forms.be_spoke_form.preview');
            Route::get('/active/{id}', [BeSpokeFormsController::class, 'active'])->name('head_office.be_spoke_forms.be_spoke_form.active');
            Route::get('/archive/{id}', [BeSpokeFormsController::class, 'archive'])->name('head_office.be_spoke_forms.be_spoke_form.archived');
            Route::any('/records/{form_id}', [BeSpokeFormsController::class, 'records'])->name('head_office.be_spoke_forms.be_spoke_form.records');
            Route::any('/form_template/stage/delete/{id}', [BeSpokeFormsController::class, 'deleteStage'])->name('head_office.be_spoke_forms_templates.form_stage_delete');
            Route::get('/form_template/stages/{stage_id}/', [BeSpokeFormsController::class, 'stageGroups'])->name('head_office.be_spoke_forms_templates.stage_groups');

            Route::any('/form_template/stages/save/{stage_id}', [BeSpokeFormsController::class, 'stageGroupSave'])->name('head_office.be_spoke_forms_templates.stage_groups.save');
            route::post('default_task/stage/save/', [BeSpokeFormsController::class, 'stage_default_task_save'])->name('head_office.be_spoke_form.stage.default_task_save');
            route::post('default_task/stage/update/', [BeSpokeFormsController::class, 'stage_default_task_update'])->name('head_office.be_spoke_form.stage.default_task_update');
            route::get('default_task/stage/delete/{id}', [BeSpokeFormsController::class, 'stage_default_task_delete'])->name('head_office.be_spoke_form.stage.default_task_delete');
            route::post('default_task/save/', [BeSpokeFormsController::class, 'default_task_save'])->name('head_office.be_spoke_form.default_task_save');
            Route::post('/add-loctions', [BeSpokeFormsController::class, 'assign_locations'])->name('head_office.assign_locations');
            Route::get('/remove-loctions/{id}/{form_id}', [BeSpokeFormsController::class, 'remove_locations'])->name('head_office.removeLocations');

            #For Form Builder
            Route::get('/get_form_json/{id}', [BeSpokeFormsController::class, 'getFormJson'])->name('head_office.be_spoke_forms_templates.get_form_json');
            Route::get('/get_form_task_json/{id}', [BeSpokeFormsController::class, 'getFormTaskJson'])->name('head_office.be_spoke_forms_templates.get_form+task_json');
            Route::get('/del_form_task_json/{id}', [BeSpokeFormsController::class, 'delFormTaskJson'])->name('head_office.be_spoke_forms_templates.del_form_task_json');
            Route::get('/get_form_json_temp/{id}', [BeSpokeFormsController::class, 'getFormJsonTemp'])->name('head_office.be_spoke_forms_templates.get_form_json_temp');
            Route::get('/get_form_task_json_temp/{id}', [BeSpokeFormsController::class, 'getFormTaskJsonTemp'])->name('head_office.be_spoke_forms_templates.get_form_task_json_temp');
            Route::post('/save_form_json/{id}', [BeSpokeFormsController::class, 'saveFormJson'])->name('head_office.be_spoke_forms_templates.save_form_json');
            Route::post('/save_form_task_json/{id}', [BeSpokeFormsController::class, 'saveFormTaskJson'])->name('head_office.be_spoke_forms_templates.save_form_task_json');
            Route::post('/save_form_json_temp/{id}', [BeSpokeFormsController::class, 'saveFormJsonTemp'])->name('head_office.be_spoke_forms_templates.save_form_json_temp');
            Route::post('/save_form_task_json_temp/{id}', [BeSpokeFormsController::class, 'saveFormTaskJsonTemp'])->name('head_office.be_spoke_forms_templates.save_form_task_json_temp');
            Route::post('/test_submit_form/{id}', [BeSpokeFormsController::class, 'testSubmitFormJson'])->name('head_office.be_spoke_forms_templates.test_submit_form_json');
            Route::post('/submit_form_edit/{id}', [BeSpokeFormsController::class, 'submitLocationFormJson_edit'])->name('head_office.be_spoke_forms.location_submit_form_json_edit');


            Route::group(['prefix' => 'bespokeforms',], function () {

                route::post('default_task/store', [DefaultDocumentController::class, 'store'])->name('default_documents.default_document.store');
                route::post('default_task/links/store', [DefaultDocumentController::class, 'store_links'])->name('default_links.default_link.store');
                route::get('default_task/delete/{id}', [DefaultDocumentController::class, 'delete'])->name('default_documents.default_document.delete');
                route::get('default_task/links/delete/{id}', [DefaultDocumentController::class, 'delete_link'])->name('default_links.default_link_delete');
                route::get('default_task/links/activate/{id}', [DefaultDocumentController::class, 'activate_link'])->name('default_links.default_link_activate');

            });
            #head office stage questions

            Route::get('form_template/stages/{stage_id}/{group_id}/questions', [BeSpokeFormsController::class, 'stageQuestionsIndex'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions');

            Route::post('form_template/stages/{stage_id}/{group_id}/question/save', [BeSpokeFormsController::class, 'stageQuestionsSave'])
                ->name('head_office.be_spoke_forms_templates.form_stage_question.save');

            Route::get('form_template/stages/question/{question_id}/', [BeSpokeFormsController::class, 'stageQuestionEdit'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.edit');

            Route::get('form_template/stages/question/delete/{question_id}/', [BeSpokeFormsController::class, 'stageQuestionDelete'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.delete');


            Route::get('/form_template/stages/question/actions/{question_id}/', [BeSpokeFormsController::class, 'questionActionIndex'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.action');

            Route::get('/form_template/stages/question/actions/{question_id}/condition/{condition_id}/', [BeSpokeFormsController::class, 'questionActionEdit'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.action.edit');

            Route::post('/form_template/stages/question/actions/{question_id}/save', [BeSpokeFormsController::class, 'questionActionSave'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.action.save');

            Route::get('/form_template/stages/question/actions/action_type/details', [BeSpokeFormsController::class, 'questionActionTypeDetail'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.action.type_details');

            Route::get('/form_template/stages/question/actions/condition/delete/{condition_id}', [BeSpokeFormsController::class, 'actionConditionDelete'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.action.condition.delete');

            Route::get('/form_template/stages/emails/attachment/{action_id}', [BeSpokeFormsController::class, 'viewEmailAttachment'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.email.attachment.view');

            Route::get('/form_template/stages/emails/attachment/{action_id}/delete', [BeSpokeFormsController::class, 'deleteEmailAttachment'])
                ->name('head_office.be_spoke_forms_templates.form_stage_questions.email.attachment.delete');


            Route::any('/form_template/group/delete/{id}', [BeSpokeFormsController::class, 'deleteGroup'])
                ->name('head_office.be_spoke_forms_templates.form_group_delete');


            Route::get('head_office/default_task/default_task_delete/{id}', [BeSpokeFormsController::class, 'default_task_delete'])->name('head_office.default_task.default_task_delete');
        });

        Route::group(['middleware' => ['check_permissions:company']], function () {
            #organisation settings
            Route::group(['prefix' => 'organisation_settings',], function () {
                Route::get('/', [OrganisationSettingController::class, 'index'])->name('organisation_settings.organisation_setting.index');
                route::get('/create', [OrganisationSettingController::class, 'create'])->name('organisation_settings.organisation_setting.create');
                route::get('/edit/{id}', [OrganisationSettingController::class, 'edit'])->name('organisation_settings.organisation_setting.edit')->where('id', '[0-9]+');
                route::any('/update/{id?}', [OrganisationSettingController::class, 'update'])->name('organisation_settings.organisation_setting.update')->where('id', '[0-9]+');
                route::post('/store', [OrganisationSettingController::class, 'store'])->name('organisation_settings.organisation_setting.store');
                route::any('/delete/{id}', [OrganisationSettingController::class, 'delete'])->name('organisation_settings.organisation_setting.delete')->where('id', '[0-9]+');
            });

            Route::get('/company/info', [HeadOfficeController::class, 'company_info'])->name('head_office.company_info');
     
            Route::get('/company/contacts', [HeadOfficeController::class, 'contacts_merge'])->name('head_office.contacts_merge');

            Route::post('/apply_theme', [HeadOfficeController::class, 'apply_theme'])->name('head_office.company_info.apply_theme');
            Route::post('/change_percentage_merge', [HeadOfficeController::class, 'change_percentage_merge'])->name('head_office.company_info.change_percentage_merge');
            Route::get('/company/loc/template_download', [HeadOfficeController::class, 'template_download'])->name('head_office.template_download');
            Route::post('/company/loc/template_submit', [HeadOfficeController::class, 'template_submit'])->name('head_office.template_submit');
            Route::get('/my/organisation', [HeadOfficeOrganizationController::class, 'index'])->name('head_office.my_organisation');
            Route::get('/organisation/structure/{parent_id?}', [HeadOfficeOrganizationController::class, 'organisation_structure'])->name('head_office.organisation_structure');
            Route::get('/organisation/tags', [HeadOfficeOrganizationController::class, 'organisation_tags'])->name('head_office.organisation_tags');
            Route::get('/organisation/settings', [HeadOfficeOrganizationController::class, 'organisation_settings'])->name('head_office.organisation_settings');
            Route::get('/organisation/personalise_location/{id}', [HeadOfficeOrganizationController::class, 'personalise_location'])->name('head_office.location.personalise_location');
            Route::get('/organisation/head_office.location_preview', [HeadOfficeOrganizationController::class, 'location_preview'])->name('head_office.location.location_preview');
            Route::get('/head_office/location/create', [HeadOfficeLocationsController::class, 'create'])->name('head_office.location.create');


            Route::get('/head_office/location/color_css.css/{location_id}', [HeadOfficeOrganizationController::class, 'location_color_css'])->name('head_office.location.color_css');
            # Head office tags, and organisation.
            Route::post('/organisation/tag_category/save', [HeadOfficeOrganizationController::class, 'tag_category_save'])->name('head_office.tag_category_save');
            Route::get('/organisation/tag_category/delete/{id}', [HeadOfficeOrganizationController::class, 'tag_category_delete'])->name('head_office.tag_category_delete');
            Route::post('/organisation/tag_category/tags/{id}/save/tag', [HeadOfficeOrganizationController::class, 'save_tag'])->name('head_office.orginisation.save_tag');
            Route::get('/organisation/tag_category/{category_id}/tags/delete/tag/{id}', [HeadOfficeOrganizationController::class, 'delete_tag'])->name('head_office.orginisation.delete_tag');
            Route::get('/organisation/tag_category/{category_id}/tags/form/', [HeadOfficeOrganizationController::class, 'add_tag_form'])->name('head_office.orginisation.add_tag_form');
            Route::post('/organisation/groups/group/save/{id?}', [HeadOfficeOrganizationController::class, 'add_edit_group'])->name('head_office.organisation.group.save');
            Route::post('/organisation/groups/group/delete/{id?}', [HeadOfficeOrganizationController::class, 'delete_group'])->name('head_office.organisation.group.delete');
            Route::get('/organisation/location/groups/{id}', [HeadOfficeOrganizationController::class, 'assign_group_to_location'])->name('head_office.organisation.assign_groups');
            Route::post('/organisation/location/groups/save/{id}', [HeadOfficeOrganizationController::class, 'assign_group_to_location_save'])->name('head_office.organisation.assign_groups_save');
            Route::get('/organisation/location/groups/delete/{id}', [HeadOfficeOrganizationController::class, 'delete_group_from_location'])->name('head_office.organisation.delete_group');
            Route::get('/organisation/location/tags/{id}', [HeadOfficeOrganizationController::class, 'assign_tags_to_location'])->name('head_office.organisation.assign_tags');
            Route::post('/organisation/location/tags/save/{id}', [HeadOfficeOrganizationController::class, 'assign_tags_to_location_save'])->name('head_office.organisation.assign_tags.save');
            Route::get('/organisation/location/tags/delete/{id}', [HeadOfficeOrganizationController::class, 'delete_tag_from_location'])->name('head_office.organisation.assign_tags.delete');


            Route::get('/organisation/location/setting/{id}', [HeadOfficeOrganizationController::class, 'assign_setting_to_location'])->name('head_office.organisation.assign_setting');
            Route::post('/organisation/location/setting/save/{id}', [HeadOfficeOrganizationController::class, 'assign_setting_to_location_save'])->name('head_office.organisation.assign_setting_save');
            Route::get('/organisation/settings/location/{setting_id}/{location_id}', [HeadOfficeOrganizationController::class, 'organisation_settings_update'])->name('head_office.organisation_settings_update');
            Route::post('/organisation/settings/location/multi', [HeadOfficeOrganizationController::class, 'organisation_settings_update_multi'])->name('head_office.organisation_settings_update.multi');

            Route::post('/organisation/levels/save/', [HeadOfficeOrganizationController::class, 'save_level'])->name('head_office.organisation.save_level');
            Route::get('/preview_location_color_branding_get/{id}', [HeadOfficeOrganizationController::class, 'preview_location_color_branding_get'])->name('head_office.color_branding_get');
            Route::post('/preview_location_color_branding/{id}', [HeadOfficeOrganizationController::class, 'preview_location_color_branding'])->name('head_office.color_branding');
            Route::post('/update_location_branding', [HeadOfficeController::class, 'update_color_branding'])->name('head_office.update_head_office_branding');
            Route::post('/head_office/finance_department_detail/store', [HeadOfficeController::class, 'finance_department_detail_store'])->name('head_office.finance_department_detail.store');

            Route::get('/location/access/{id}', [HeadOfficeController::class, 'loc_access'])->name('location.access.update');
            Route::post('/location/access/multi', [HeadOfficeController::class, 'loc_access_multi'])->name('location.access.update.multi');
            Route::get('/location/password_admin/{id}', [HeadOfficeController::class, 'password_admin'])->name('location.password_admin.update');
            Route::post('/location/password_direct', [HeadOfficeController::class, 'password_direct_update'])->name('location.password_direct_update.update');
            Route::post('/location/tags/multi', [HeadOfficeController::class, 'tags_multi'])->name('location.tags.update.multi');
            Route::post('/location/archive/multi', [HeadOfficeController::class, 'archive_multi'])->name('location.archive.update.multi');
            Route::post('/location/unarchive/multi', [HeadOfficeController::class, 'unarchive_multi'])->name('location.unarchive.update.multi');
            Route::post('/location/rename/multi', [HeadOfficeController::class, 'rename_multi'])->name('location.rename.update.multi');
            Route::post('/location/delete/multi', [HeadOfficeController::class, 'delete_multi'])->name('location.delete.update.multi');
            Route::post('/location/restore/multi', [HeadOfficeController::class, 'restore_multi'])->name('location.restore.update.multi');

            Route::get('/settings', [HeadOfficeController::class, 'settings'])->name('head_office.settings');
            Route::post('/update_head_office_details', [HeadOfficeController::class, 'update_head_office_details'])->name('head_office.request_update_details');
            Route::get('/color_css.css', [HeadOfficeController::class, 'color_css'])->name('head_office.color_css');

            Route::get('/import_location/incidents/{id}', [HeadOfficeController::class, 'import_location_incidents_preview'])->name('head_office.location.import_location_incidents_preview');
            Route::get('/single_record/{id}', [HeadOfficeController::class, 'single_record'])->name('head_office.location.single_record');
            Route::post('/single_record_link', [HeadOfficeController::class, 'single_record_link'])->name('head_office.location.single_record_link');

            Route::post('/update_head_office_contact_details', [HeadOfficeController::class, 'update_head_office_contact_details'])->name('head_office.update_head_office_contact_details');
            Route::post('/update_company_timing', [HeadOfficeController::class, 'update_company_timing'])->name('head_office.update_company_timing');

        });

        Route::group(['prefix' => 'users', 'middleware' => 'check_permissions:team'], function () {
            Route::get('/', [HeadOfficeUsersController::class, 'head_office_users'])->name('head_office.head_office_users');
            Route::get('/view_drafts_user/delete-draft/{id}', [LocationController::class, 'delete_drafts'])->name('users.delete_drafts');
            Route::get('/block-users/{id}', [HeadOfficeUsersController::class, 'block_users'])->name('head_office.head_office_users.block_user');
            Route::post('/block-users/save', [HeadOfficeUsersController::class, 'block_users_save'])->name('head_office.head_office_users.block_user_save');
            Route::post('/block-users/update-comment', [HeadOfficeUsersController::class, 'block_users_comment_update'])->name('headoffice.user_block.update_comment');
            Route::post('/details/{headOffice}/update', [HeadOfficeUsersController::class, 'head_office_user_update'])->name('head_office.head_office_user_update');
            Route::post('/head-office-user/delete/{id}', [HeadOfficeUsersController::class, 'head_office_user_delete'])->name('head_office_user_delete');

            Route::get('/{headOffice}/remove', [HeadOfficeUsersController::class, 'remove_user'])
                ->name('head_offices.head_office.remove_user');
            Route::get('/profiles', [HeadOfficeUsersController::class, 'head_office_user_profiles'])->name('head_office.head_office_user_profiles');
            Route::post('/profiles/save', [HeadOfficeUsersController::class, 'head_office_profile_save'])->name('head_office.head_office_profile_save');
            Route::post('/profiles/delete', [HeadOfficeUsersController::class, 'head_office_profile_delete'])->name('head_office.head_office_profile_delete');
            Route::post('/head_office/reassign_profile', [HeadOfficeUsersController::class, 'reassignProfile'])->name('head_office.reassign_profile');

            Route::post('/access_rights/save', [HeadOfficeUsersController::class, 'head_office_access_right_save'])->name('head_office.head_office_access_right_save');
            Route::get('/access_rights/delete/{id}', [HeadOfficeUsersController::class, 'head_office_access_right_delete'])->name('head_office.head_office_access_right_delete');

            Route::get('/invite', [HeadOfficeUsersController::class, 'show_invite_user'])->name('head_office.head_office_users.show_invite_user');
            Route::post('/submit_invite', [HeadOfficeUsersController::class, 'submit_invite_user'])->name('head_office.head_office_users.submit_invite_user');
            Route::get('/resend_invite/{id}', [HeadOfficeUsersController::class, 'resend_invite_user'])->name('head_office.head_office_users.resend_invite');
            Route::get('/cancel_invite/{id}', [HeadOfficeUsersController::class, 'cancel_invite_user'])->name('head_office.head_office_users.cancel_invite');
            Route::post('/edit_invite', [HeadOfficeUsersController::class, 'edit_invite_user'])->name('head_office.head_office_users.edit_invite');
            route::post('head_office/update_user_settings/{user_id}', [HeadOfficeController::class, 'update_user_settings'])->name('head_office.update_user_settings');
            route::post('head_office/update_user_settings_locaations/{user_id}', [HeadOfficeController::class, 'update_user_assigned_locations'])->name('head_office.update_user_settings_locations');

            // Bulk Team functions 
            Route::post('/profiles_bulk_assign/save', [HeadOfficeUsersController::class, 'bulk_profile_assign'])->name('head_office.bulk_profile_assign');
            Route::post('/profiles_bulk_unassign/save', [HeadOfficeUsersController::class, 'bulk_profile_unassign'])->name('head_office.bulk_profile_unassign');

        });

        Route::group(['middleware' => 'check_permissions:location_users'], function () {

            Route::post('/search_email', [HeadOfficeController::class, 'search_email'])->name('head_office.search_email');
            Route::get('/approved/location/users', [HeadOfficeController::class, 'head_office_approved_location_users'])->name('head_office.approved_location.users');
            Route::get('/approved/location/delete/{id}', [HeadOfficeController::class, 'head_office_approved_location_delete'])->name('head_office.approved_location.delete');
            route::post('/approved/location/store/{id?}', [HeadOfficeController::class, 'store_location_user'])->name('head_office.approved_locaton.store');
        });

        Route::group(['middleware' => 'check_permissions:alerts'], function () {
            # Headoffice patient safety alerts and holding area
            Route::get('/patient_safety_alerts/', [PatientSafetyAlertsController::class, 'index'])->name('head_office.psa');
            Route::get('/patient_safety_alerts/holding_area', [PatientSafetyAlertsController::class, 'holding_area'])->name('head_office.psa.holding_area');
            Route::post('/settings/patient_safety_alerts/holding_area/on', [PatientSafetyAlertsController::class, 'holding_area_on_off'])->name('head_office.psa.holding_area_on_off');

            Route::get('/patient_safety_alerts/record/{id?}', [PatientSafetyAlertsController::class, 'record'])->name('head_office.psa.holding_area.record');
            Route::post('/patient_safety_alerts/record_save/', [PatientSafetyAlertsController::class, 'save'])->name('head_office.psa.holding_area.save');
            Route::get('/patient_safety_alerts/view/{id?}', [PatientSafetyAlertsController::class, 'view'])->name('head_office.psa.view');
            Route::get('/patient_safety_alerts/approve/{id?}', [PatientSafetyAlertsController::class, 'approve'])->name('head_office.psa.approve');
            Route::get('/patient_safety_alerts/archive/{id?}', [PatientSafetyAlertsController::class, 'archive'])->name('head_office.psa.archive');
            Route::get('/patient_safety_alerts/reject/{id?}', [PatientSafetyAlertsController::class, 'reject'])->name('head_office.psa.reject');

            Route::get('/view/notifications', [HeadOfficeController::class, 'view_notifications'])->name('headoffice.view_notifications');
            Route::get('/process/notification/url/{id}', [HeadOfficeController::class, 'process_notifcation_url'])->name('head_office.process_notifcation_url');

            Route::get('/mark/read/all/notifications', [HeadOfficeController::class, 'mark_read_all_notifications'])->name('head_office.mark_read_all_notifications');
        });

        Route::group(['middleware' => 'check_permissions:contacts'], function () {

            route::get('head_office/contact', [HeadOfficeController::class, 'contact'])->name('head_office.contact');

            route::get('head_office/view/contact/{id}', [HeadOfficeController::class, 'view_contact'])->name('head_office.contact.view');

            route::post('head_office/add_new_contact/{id?}', [HeadOfficeController::class, 'add_new_contact'])->name('head_office.contact.add_new_contact');
            route::post('head_office/address/{id}', [HeadOfficeController::class, 'add_new_address'])->name('head_office.contact.add_new_address');
            route::post('head_office/relation/{id}/{connection_id?}', [HeadOfficeController::class, 'add_new_relation'])->name('head_office.contact.add_new_relation');
            route::get('head_office/relation/delete/{connection_id}/{contact_id}', [HeadOfficeController::class, 'delete_relation'])->name('head_office.contact.delete_relation');

            route::post('head_office/assign_new_case/{id}', [HeadOfficeController::class, 'assign_new_case'])->name('head_office.contact.assign_new_case');
            route::get('head_office/delete_new_case/{case_contact_id}/{case_id}', [HeadOfficeController::class, 'delete_new_case'])->name('head_office.contact.delete_new_case');


            route::post('head_office/normal-address/{id?}', [HeadOfficeController::class, 'add_new_normal_address'])->name('head_office.contact.add_new_normal_address');
            route::get('head_office/normal-address/delete/{id}', [HeadOfficeController::class, 'add_new_normal_address_delete'])->name('head_office.contact.add_new_normal_address_delete');
            route::get('head_office/add_new_contact/delete/{id}', [HeadOfficeController::class, 'add_new_contact_delete'])->name('head_office.contact.add_new_contact_delete');
            route::get('gdpr_tags/index', [GdprController::class, 'index'])->name('head_office.gdpr.index');
            route::post('bdpr_tags/save/{id?}', [GdprController::class, 'save'])->name('head_office.gdpr.save');
            route::post('bdpr_tags/delete/{id}', [GdprController::class, 'delete'])->name('head_office.gdpr.delete');

            route::get('/contacts/index', [ContactsController::class, 'index'])->name('head_office.contacts.index');
            route::post('/contacts/create/{id?}', [ContactsController::class, 'create'])->name('head_office.contacts.create');
            route::get('/contacts/create', [ContactsController::class, 'create_contact'])->name('head_office.contacts.create_contact');
            route::get('/contacts/edit/{id}', [ContactsController::class, 'edit'])->name('head_office.contacts.edit');
            route::post('/contacts/create_group/{id?}', [ContactsController::class, 'create_group'])->name('head_office.contacts.create_group');
            route::post('/contacts/delete_group/{id}', [ContactsController::class, 'delete_group'])->name('head_office.contacts.delete_group');
            route::post('/contacts/create_tag/{id?}', [ContactsController::class, 'create_tag'])->name('head_office.contacts.create_tag');
            route::post('/contacts/delete_tag/{id}', [ContactsController::class, 'delete_tag'])->name('head_office.contacts.delete_tag');
            route::get('/contacts/favourite_contact/{contact_id}', [ContactsController::class, 'favourite_contact'])->name('head_office.contacts.favourite_contact');
            route::get('/contacts/delete_contact/{id}', [ContactsController::class, 'delete_contact'])->name('head_office.contacts.delete_contact');
            route::post('/contacts/save_comment/{id?}', [ContactsController::class, 'save_comment'])->name('head_office.contacts.save_comment');
            Route::get('/contacts/comment/delete/{comment_id?}', [ContactsController::class, 'delete_comment'])->name('head_office.contacts.delete_comment');
            Route::any('head_office/contacts/mentions/list', [ContactsController::class, 'users_list'])->name('head_office.contacts.users_list');
            
            route::post('/contacts/delete_bulk', [ContactsController::class, 'delete_bulk'])->name('head_office.contacts.delete_bulk');
            route::post('/contacts/archive_bulk', [ContactsController::class, 'archive_contact_bulk'])->name('head_office.contacts.archive_bulk');
            route::post('/contacts/unarchive_bulk', [ContactsController::class, 'unarchive_contact_bulk'])->name('head_office.contacts.unarchive_bulk');
            route::post('/contacts/restore_bulk', [ContactsController::class, 'restore_contact_bulk'])->name('head_office.contacts.restore_bulk');
            route::post('/contacts/assign_tags_bulk', [ContactsController::class, 'assign_tags_bulk'])->name('head_office.contacts.assign_tags_bulk');
            route::post('/contacts/assign_users_bulk', [ContactsController::class, 'assign_users_bulk'])->name('head_office.contacts.assign_users_bulk');
            route::post('/contacts/assign_group_bulk', [ContactsController::class, 'assign_group_bulk'])->name('head_office.contacts.assign_group_bulk');
            route::post('/contacts/create_address/{id?}', [ContactsController::class, 'create_address'])->name('head_office.contacts.create_address');
            route::get('/address/edit/{id}', [ContactsController::class, 'addressEdit'])->name('head_office.address.edit');
            route::post('/contacts/delete_address/{id}', [ContactsController::class, 'delete_address'])->name('head_office.contacts.delete_address');
            route::post('/contacts/address/save_comment/{id?}', [ContactsController::class, 'save_address_comment'])->name('head_office.address.save_comment');
            Route::get('/address/comment/delete/{comment_id?}', [ContactsController::class, 'delete_address_comment'])->name('head_office.address.delete_comment');
            route::get('/contacts/view/{id}', [ContactsController::class, 'view'])->name('head_office.contacts.view');
            Route::get('/contacts/view_timeline/{id}', [ContactsController::class, 'contact_view_timeline'])->name('head_office.contact_view_timeline');
            Route::get('/contacts/view_intelligence/{id}', [ContactsController::class, 'contact_view_intelligence'])->name('head_office.contact_intelligence');
            Route::get('/contacts/view_matchs/{id}', [ContactsController::class, 'contact_view_matchs'])->name('head_office.contact_matchs');
        });

        Route::group(['middleware' => ['check_permissions:locations']], function () {
            Route::get('/locations', [HeadOfficeController::class, 'locations_page'])->name('head_office.locations_page');
            Route::get('/location/view/{id}', [HeadOfficeController::class, 'location_page_view'])->name('head_office.location_page_view');
            Route::post('/location/toggle-status/{id}', [HeadOfficeController::class, 'toggleLocationStatus'])->name('head_office.toggleLocationStatus');


            Route::post('/location/comment/save', [HeadOfficeController::class, 'location_comment_save'])->name('head_office.location_comment.save');
            Route::get('/location/comment/delete/{comment_id?}', [ContactsController::class, 'delete_comment_location'])->name('head_office.location.delete_comment');
            Route::post('/location/location_info_update', [ContactsController::class, 'location_info_update'])->name('head_office.location.update');
            Route::post('/update-address', [ContactsController::class, 'updateAddress'])->name('update.address');
            Route::get('/location/view_timeline/{id}', [HeadOfficeController::class, 'location_page_view_timeline'])->name('head_office.location_page_view_timeline');
        });



        Route::post('/update_head_office_location_details', [HeadOfficeController::class, 'update_head_office_location_details'])->name('head_office.update_head_office_location_details');
        Route::post('/update_head_office_case_add', [HeadOfficeController::class, 'add_new_case_investigator'])->name('head_office.user_incident_setting_add');
        Route::get('/delete_head_office_case_add/{id}', [HeadOfficeController::class, 'delete_case_investigator'])->name('head_office.delete_case_investigitor');

        Route::get('/dashboard', [HeadOfficeController::class, 'dashboard'])->name('head_office.dashboard');
        Route::get('/ho_view_profile', [HeadOfficeController::class, 'ho_view_profile'])->name('head_office.view_profile');
        Route::get('/activity-logs', [HeadOfficeController::class, 'ho_view_profile_logs'])->name('head_office.view_profile_logs');
        Route::get('/hide_email/{type}', [HeadOfficeController::class, 'hide_email'])->name('contact.hide.email');
        Route::get('/hide_phone/{type}', [HeadOfficeController::class, 'hide_phone'])->name('contact.hide.phone');
        Route::post('/add_contact_detail', [HeadOfficeController::class, 'add_contact_detail'])->name('head_office.add_contact_detail');
        Route::post('/update_email', [HeadOfficeController::class, 'update_email'])->name('head_office.update_email');
        Route::post('/update_email_user_settings', [HeadOfficeController::class, 'update_email_user_settings'])->name('head_office.update_email_user_settings');
        Route::post('/update_position', [HeadOfficeController::class, 'update_position'])->name('head_office.update_position');
        Route::post('/remove_session_records', [HeadOfficeController::class, 'remove_session_records'])->name('head_office.remove_session_records');
        Route::post('/log_session_records', [HeadOfficeController::class, 'log_session_records'])->name('head_office.log_session_records');
        Route::post('/update_about', [HeadOfficeController::class, 'update_about'])->name('head_office.update_about');
        Route::post('/update_location', [HeadOfficeController::class, 'update_location'])->name('head_office.update_location');
        Route::post('/update_area', [HeadOfficeController::class, 'update_area'])->name('head_office.update_area');
        Route::post('/update_area_user_settings', [HeadOfficeController::class, 'update_area_user_settings'])->name('head_office.update_area_user_settings');
        Route::post('/update_profile', [HeadOfficeController::class, 'update_profile'])->name('head_office.update_profile');
        Route::get('/delete_area/{id}', [HeadOfficeController::class, 'delete_area'])->name('head_office.delete_area');
        Route::get('/delete_area_user_settings/{id}/{hou_id}', [HeadOfficeController::class, 'delete_area_user_settings'])->name('head_office.delete_area_user_settings');
        Route::get('/delete_contact/{id}', [HeadOfficeController::class, 'delete_contact'])->name('head_office.delete_contact');
        Route::get('/delete_contact_users/{id}/{hou_id}', [HeadOfficeController::class, 'delete_contact_users_settings'])->name('head_office.delete_contact_user_settings');
        Route::post('/update_phone', [HeadOfficeController::class, 'update_phone'])->name('head_office.update_phone');
        Route::post('/head_office_add_area', [HeadOfficeController::class, 'head_office_add_area'])->name('head_office.head_office_add_area');
        Route::post('/update_ho_timing', [HeadOfficeController::class, 'update_ho_timing'])->name('head_office.update_ho_timing');
        Route::post('/update_form_details', [HeadOfficeController::class, 'update_form_details'])->name('head_office.update_form_details');
        Route::post('/update_status', [HeadOfficeController::class, 'updateStatus'])->name('head_office.update_status')->middleware('throttle:5,1');
        Route::post('/update_active', [HeadOfficeController::class, 'updateActive'])->name('head_office.update_active')->middleware('throttle:15,1');
        Route::post('/update_disturb', [HeadOfficeController::class, 'updateDisturb'])->name('head_office.update_disturb')->middleware('throttle:10,1');
        Route::post('/update_head_office_user_holidays', [HeadOfficeController::class, 'update_head_office_user_holidays'])->name('head_office.update_head_office_user_holidays');
        Route::get('/update_head_office_user_bank_holiday_selection', [HeadOfficeController::class, 'update_head_office_user_bank_holiday_selection'])->name('head_office.update_head_office_user_bank_holiday_selection');
        Route::get('/delete_head_office_user_holiday/{id}', [HeadOfficeController::class, 'delete_head_office_user_holiday'])->name('head_office.delete_head_office_user_holiday');

        Route::get('/head_office_user_login_session', [HeadOfficeController::class, 'head_office_user_login_session'])->name('head_office.head_office_user_login_session');

        Route::get('/end_head_office_user_session/{id}', [HeadOfficeController::class, 'end_head_office_user_session'])->name('head_office.end_head_office_user_session');
        Route::post('/end_head_office_user_session_all', [HeadOfficeController::class, 'end_head_office_user_session_all'])->name('head_office.end_head_office_user_session_all');






        route::post('case_docuemnt/store', [CaseManagerController::class, 'store_document'])->name('case_docuemnts.case_docuemnt.case_docuemnt_store');
        route::get('case_docuemnt/delete/{id}', [CaseManagerController::class, 'delete_document'])->name('case_docuemnts.case_docuemnt.case_docuemnt_delete');
        route::get('case_docuemnt/activate/{id}', [CaseManagerController::class, 'activate_document'])->name('case_docuemnts.case_docuemnt.case_docuemnt_activate');


        Route::get('/change_password', [HeadOfficeController::class, 'update_password_view'])->name('head_office.update_password_view');
        Route::post('/update_password', [HeadOfficeController::class, 'update_password'])->name('head_office.request_update_password');


        Route::get('/verified_devices', [HeadOfficeController::class, 'verified_devices'])->name('head_office.verified_devices');

        Route::post('/update_head_office/default_fish_bone_question_save', [HeadOfficeController::class, 'default_fish_bone_question_save'])->name('head_office.setting.default_fish_bone_question_save');
        Route::get('/update_head_office/default_fish_bone_question/delete/{id}', [HeadOfficeController::class, 'default_fish_bone_question_delete'])->name('head_office.setting.default_fish_bone_question_delete');

        Route::post('/update_head_office/default_five_whys_question_save', [HeadOfficeController::class, 'default_five_whys_question_save'])->name('head_office.setting.default_five_whys_question_save');
        Route::get('/update_head_office/default_five_whys_question/delete/{id}', [HeadOfficeController::class, 'default_five_whys_question_delete'])->name('head_office.setting.default_five_whys_question_delete');





        # Prefix casemanager.


        Route::group(['prefix' => 'near_miss/manager'], function () {
            Route::get('/{id}', [NearMissManagerController::class, 'near_miss_manager'])->name('head_office.near_miss_manager');
            Route::post('update/{id}', [NearMissManagerController::class, 'near_miss_manager_update'])->name('near_miss_manager.update');
            Route::post('/info_update', [NearMissManagerController::class, 'name_update'])->name('head_office.update_near_miss.name');
            Route::post('/add_setting', [NearMissManagerController::class, 'add_setting'])->name('head_office.near_miss.add_setting');
            Route::get('/delete_setting/{id}', [NearMissManagerController::class, 'delete_setting'])->name('head_office.update_near_miss.delete');
            Route::get('/edit_setting/{id}', [NearMissManagerController::class, 'edit_setting'])->name('head_office.update_near_miss.edit');
            Route::get('/status_setting/{id}', [NearMissManagerController::class, 'status_setting'])->name('head_office.update_near_miss.status');
            Route::post('/head_office/near_miss/assign_location', [NearMissManagerController::class, 'assign_location'])->name('head_office.near_miss.assign_location');
            Route::post('/template_submit', [NearMissManagerController::class, 'template_submit'])->name('head_office.near_miss.template_submit');
            Route::get('/near_active/{id}', [NearMissManagerController::class, 'near_active'])
                ->name('head_office.be_spoke_forms.near_miss.active');
        });


        Route::group(['prefix' => 'case/manager','middleware'=> ['check_permissions:case_manager']], function () {

            Route::get('/', [CaseManagerController::class, 'index'])->name('case_manager.index');
            Route::get('/remove_case_access/{id}/{user_id}', [CaseManagerController::class, 'remove_case_access'])->name('case_manager.remove_case_access');
            Route::get('/overview', [CaseManagerController::class, 'overview'])->name('case_manager.overview');
            Route::get('/case_updates', [CaseManagerController::class, 'case_updates'])->name('case_manager.case_updates');
            Route::get('/case_archives', [CaseManagerController::class, 'case_archives'])->name('case_manager.case_archives');
            Route::get('/case/record/{id?}', [CaseManagerController::class, 'case_record'])->name('case_manager.case_record');
            Route::post('/case/record/save', [CaseManagerController::class, 'case_record_save'])->name('case_manager.case_record_save');

            Route::get('/view/{id}', [CaseManagerController::class, 'view'])->name('case_manager.view');
            Route::post('/view/close_case/{id}/{type}', [CaseManagerController::class, 'close_case'])->name('case_manager.view.close_case');
            Route::post('/view/close_case/{id}', [CaseManagerController::class, 'reject_case_close_request'])->name('case_manager.view.reject_case_close_request');
            Route::post('/view/accept_case_close_request/{id}', [CaseManagerController::class, 'accept_case_close_request'])->name('case_manager.view.accept_case_close_request');
            Route::post('/view/close_cases', [CaseManagerController::class, 'close_cases'])->name('case_manager.view.close_cases');
            Route::get('/view/report/{id}', [CaseManagerController::class, 'view_report'])->name('case_manager.view_report');
            Route::get('/edit/report/{id}', [CaseManagerController::class, 'edit_report'])->name('case_manager.edit_report');
            Route::post('/edit/report/save/{id}', [CaseManagerController::class, 'edit_report_save'])->name('case_manager.edit_report_save');

            Route::post('/view/close_case_bulk', [CaseManagerController::class, 'close_case_bulk'])->name('case_manager.view.close_case_bulk');
            Route::post('/case/transfer_case_responsibity_bulk', [CaseManagerController::class, 'transfer_case_responsibity_bulk'])->name('case_manager.transfer_case_responsibity_bulk');
            Route::post('/case/share_case_responsibity_bulk', [CaseManagerController::class, 'share_case_responsibity_bulk'])->name('case_manager.share_case_responsibity_bulk');
            Route::post('/case/open_case_bulk', [CaseManagerController::class, 'open_case_bulk'])->name('case_manager.open_case_bulk');
            Route::post('/case/case_approval_bulk', [CaseManagerController::class, 'case_approval_bulk'])->name('case_manager.case_approval_bulk');
            Route::post('/case/archive_bulk', [CaseManagerController::class, 'archive_bulk'])->name('case_manager.archive_bulk');
            Route::post('/case/unarchive_bulk', [CaseManagerController::class, 'unarchive_bulk'])->name('case_manager.unarchive_bulk');
            Route::get('/case/archive_case/{id}', [CaseManagerController::class, 'archive_case'])->name('case_manager.archive_case');
            Route::get('/case/unarchive/{id}', [CaseManagerController::class, 'unarchive_case'])->name('case_manager.unarchive_case');

            Route::any('/case/export_cases_bulk', [CaseManagerController::class, 'export_cases_bulk'])->name('case_manger.export_cases_bulk');
            Route::post('/case/generate_link', [CaseManagerController::class, 'generate_transfer_links'])->name('case_manager.generate_transfer_links');
            Route::post('/import_cases', [CaseManagerController::class, 'import_cases'])->name('case_manager.import_cases');
            Route::post('/add_cases', [CaseManagerController::class, 'add_cases'])->name('case_manager.copy_cases');

            Route::get('/view/root_cause_analysis/{id}', [CaseManagerController::class, 'view_root_cause_analysis'])->name('case_manager.view_root_cause_analysis');
            Route::get('/view/sharing/{id}', [CaseManagerController::class, 'view_sharing'])->name('case_manager.view_sharing');
            Route::get('/view/intelligence/{id}', [CaseManagerController::class, 'view_intelligence'])->name('case_manager.view_intelligence');
            Route::get('/view/drafts/{id}', [CaseManagerController::class, 'view_drafts'])->name('case_manager.view_drafts');


            Route::post('/view/intelligence/mrege_contact', [CaseManagerController::class, 'mrege_contact'])->name('case_manager.intelligence.mrege_contact');

            Route::post('/view/comment/save/{comment_id?}', [CaseManagerController::class, 'save_comment'])->name('case_manager.save_comment');
            Route::get('/view/unseen_comment/{id}', [CaseManagerController::class, 'unseen_comment'])->name('case_manager.unseen_comment');
            Route::get('/view/seen_comment/{comment_id}', [CaseManagerController::class, 'seen_comment'])->name('case_manager.seen_comment');
            Route::post('/view/comment/save_draft/{comment_id?}', [CaseManagerController::class, 'save_comment_draft'])->name('case_manager.save_comment_draft');

            Route::get('/random_link/{id}', [CaseManagerController::class, 'random_link'])->name('case_manager.random_link');
            Route::get('/tracking_link/delete/{id?}', [CaseManagerController::class, 'delete_tracking_link'])->name('head_office.tracking_link.delete');
            Route::get('/tracking_link/active/{id?}', [CaseManagerController::class, 'active_tracking_link'])->name('head_office.tracking_link.active');
            Route::post('/tracking_link/update_link', [CaseManagerController::class, 'update_tracking_link'])->name('headoffice.tracking_link.update_link');


            Route::get('/view/comment/delete/{comment_id?}', [CaseManagerController::class, 'delete_comment'])->name('case_manager.delete_comment');
            Route::post('/view/comment/delete_multi', [CaseManagerController::class, 'delete_comment_multi'])->name('case_manager.delete_comment_multi');
            Route::get('/view/comment/requestInformation/{id}', [CaseManagerController::class, 'view_comment'])->name('headOffice.requestInformation.comment.view');
            Route::get('/view/comment/continue_comment/{comment_id?}', [CaseManagerController::class, 'continue_comment'])->name('case_manager.continue_comment');
            Route::any('/mentions/list', [CaseManagerController::class, 'users_list'])->name('case_manager.users_list');

            Route::post('/view/task/save/{task_id?}', [CaseManagerController::class, 'save_task'])->name('case_manager.save_task');
            Route::post('/view/task/assign_task_user', [CaseManagerController::class, 'assign_task_user'])->name('case_manager.assign_task_user');
            Route::get('/view/task/delete/{task_id?}', [CaseManagerController::class, 'delete_task'])->name('case_manager.delete_task');

            Route::get('/view/task/mark/complete/{stage_id}/{task_id?}', [CaseManagerController::class, 'change_status'])->name('case_manager.task.change_status');
            Route::post('/case/link_cases', [CaseManagerController::class, 'link_cases'])->name('head_office.link_cases');
            Route::post('/case/un_link_cases', [CaseManagerController::class, 'unlink_cases'])->name('head_office.un_link_cases');


            Route::get('/link/delete/{task_id}', [CaseManagerController::class, 'delete_link'])->name('links.link.delete');
            Route::post('/link/update/{task_id}', [CaseManagerController::class, 'update_link'])->name('links.link.update');

            Route::get('/link/removeable_links', [CaseManagerController::class, 'removeable_links'])->name('links.link.removeable_links');

            route::get('root_cause_analysis/index', [HeadOfficeController::class, 'root_cause_analysis'])->name('head_office.root_cause_analysis.index');

            route::post('root_cause_analysis/save', [HeadOfficeController::class, 'root_cause_analysis_save'])->name('head_office.root_cause_analysis.save');
            route::post('root_cause_analysis/request_new/{id}/{root_cause_analysis_id}', [CaseManagerController::class, 'request_new_analysis'])->name('head_office.request_new_analysis.request_new');
            route::post('route_cause_analysis/request/fish_bone/{id}', [CaseManagerController::class, 'request_fish_bone'])->name('route_cause_analysis.request.fish_bone');
            route::post('route_cause_analysis/request/five_whys/{id}', [CaseManagerController::class, 'request_five_whys'])->name('route_cause_analysis.request.five_whys');

            route::post('route_cause_analysis/request/fish_bone/edit/{case_id}/{root_cause_analysis_id}', [CaseManagerController::class, 'request_fish_bone_edit'])->name('route_cause_analysis.request.fish_bone_edit');
            route::post('route_cause_analysis/request/five_whys/edit/{case_id}/{root_cause_analysis_id}', [CaseManagerController::class, 'request_five_whys_edit'])->name('route_cause_analysis.request.five_whys_edit');
            route::get('route_cause_analysis/view/{case_id}/{root_cause_analysis_id}', [CaseManagerController::class, 'view_root_cause_analysis_results'])->name('view_root_cause_analysis_results');
            route::get('request_information/{id}', [CaseManagerController::class, 'request_information'])->name('case_manager.request_information');
            route::get('comment_drafts/{id}', [CaseManagerController::class, 'comment_drafts'])->name('head_office.case.comment_drafts');
            route::get('comment_links/{id}', [CaseManagerController::class, 'comment_links'])->name('head_office.case.comment_links');
            route::get('requested_informations/{id}', [CaseManagerController::class, 'requested_informations'])->name('head_office.case.requested_informations');
            route::get('requested_informations/single_request/{case_id}/{id}', [CaseManagerController::class, 'requested_information'])->name('head_office.case.requested_information');
            route::get('requested_informations/{case_id}/{id}', [CaseManagerController::class, 'requested_information_delete'])->name('head_office.case.requested_information_delete');
            route::get('requested_informations/edit_request/{case_id}/{id}', [CaseManagerController::class, 'requested_information_edit'])->name('head_office.case.edit_request');

            route::post('search_user', [CaseManagerController::class, 'search_user'])->name('search_user');
            route::post('request_information_save/{case_id}', [CaseManagerController::class, 'request_information_save'])->name('case_manager.request_information_save');
            Route::get('/headoffice/view/attachment/{id}', [HeadOfficeController::class, 'viewAttachment'])->name('headoffice.view.attachment');
            route::post('request_information_update/{case_id}/{request_id}', [CaseManagerController::class, 'request_information_update'])->name('case_manager.request_information_update');
            Route::get('/statement/{case_id}/{request_id}', [CaseManagerController::class, 'single_statement'])->name('head_office.statement.single_statement');
            Route::get('/statement/edit/{case_id}/{request_id}', [CaseManagerController::class, 'single_statement_edit'])->name('head_office.statement.single_statement_edit');
            Route::get('/statement/delete/{case_id}/{request_id}', [CaseManagerController::class, 'single_statement_delete'])->name('head_office.statement.single_statement_delete');
            Route::get('/case/share/{id}', [CaseManagerController::class, 'share_case'])->name('head_office.share.case');
            Route::get('/case/share/revoke_access/{case_id}/{id}', [CaseManagerController::class, 'revoke_access'])->name('head_office.share_case.revoke_access');
            Route::get('/share_case/{case_id}/{edit_id?}', [CaseManagerController::class, 'share_case_view'])->name('head_office.share_case.share_case_view');

            Route::post('/case/share_case_responsibity/{case_handler_id}/{case_id}', [CaseManagerController::class, 'share_case_responsibity'])->name('case_manager.share_case_responsibity');
            Route::post('/case/transfer_case_responsibity/{case_handler_id}/{case_id}', [CaseManagerController::class, 'transfer_case_responsibity'])->name('case_manager.transfer_case_responsibity');
            Route::post('/case/remove_case_handler/{case_id}', [CaseManagerController::class, 'remove_case_handler'])->name('case_manager.remove_case_handler');
            Route::post('/case/remove_any_case_handler/{case_id}', [CaseManagerController::class, 'remove_any_case_handler'])->name('case_manager.remove_any_case_handler');
            Route::get('/case/remove_owner/{case_id}', [CaseManagerController::class, 'remove_owner'])->name('case_manager.remove_owner');

            Route::post('/case/add_interested_parties/{case_id}', [CaseManagerController::class, 'add_interested_parties'])->name('case_manager.add_interested_parties');
            Route::get('/case/delete_interested_parties/{case_id}/{party_id}', [CaseManagerController::class, 'delete_interested_party'])->name('case_manager.delete_interested_parties');
            Route::post('/case/edit_interested_parties/{case_id}/{party_id}', [CaseManagerController::class, 'edit_interested_parties'])->name('case_manager.edit_interested_parties');

            Route::post('/head_office/share_case/{case_id}/{id}/comment', [CaseManagerController::class, 'share_case_comment'])->name('head_office.share_case.share_case_comment');

            // Related to NHS LFPSE
            Route::get('/submit_nhs_lfpse/{id}', [CaseManagerController::class, 'submit_nhs_lfpse'])->name('case_manager.submit_nhs_lfpse');
            Route::post('/delete_nhs_lfpse/{id}', [CaseManagerController::class, 'delete_nhs_lfpse'])->name('case_manager.delete_nhs_lfpse');
            Route::post('/submit_nhs_lfpse_bulk', [CaseManagerController::class, 'submit_nhs_lfpse_bulk'])->name('case_manager.submit_nhs_lfpse_bulk');
            Route::get('/request_information_save/{case_id}', [CaseManagerController::class, 'getLocationRelatedToHeadOffice']);
        });


        route::group(['prefix' => 'shared_case_approved_email'], function () {
            route::post('share_case_approved_email/{id}', [SharedCaseApprovedEmailController::class, 'store'])->name('share_emails.share_email.store');
            route::get('share_case_approved_email/delete/{id}', [SharedCaseApprovedEmailController::class, 'delete'])->name('share_emails.share_email.delete');
        });





        route::post('share_case/{case_id}/{edit_id?}', [CaseManagerController::class, 'share_case'])->name('head_office.case.share_case');
        route::get('share_case_delete/{case_id}/{share_id}', [CaseManagerController::class, 'share_case_delete'])->name('head_office.case.share_case_delete');
        route::post('share_case_edit_duration/{case_id}/{share_id}', [CaseManagerController::class, 'share_case_edit_duration'])->name('head_office.case.share_case_edit_duration');
        route::post('share_case_reject/{case_id}/{share_id}/{extension_id}', [CaseManagerController::class, 'share_case_reject'])->name('head_office.case.share_case_reject');
        route::post('share_case_accept/{case_id}/{share_id}/{extension_id}', [CaseManagerController::class, 'share_case_accept'])->name('head_office.case.share_case_accept');

    });
});




////*****    */
Route::get('/form_builder/{id}', [BeSpokeFormsController::class, 'form_test'])->name('head_office.be_spoke_form.form_test');
Route::get('/form_nhs', [BeSpokeFormsController::class, 'form_nhs'])->name('head_office.be_spoke_form.form_tests_nhs');
Route::get('/form_submit', [BeSpokeFormsController::class, 'form_submit'])->name('head_office.be_spoke_form.form_submit');


Route::domain('{subdomain}.qi-tech.co.uk')->group(function () {
    // Generic subdomain routes here (e.g., wildcard subdomains)
    Route::get('/login', function ($subdomain) {
        Auth::guard('web')->logout();
        Auth::guard('user')->logout();
        if (!isset($subdomain) || $subdomain == 'dev' || $subdomain == 'www') {
            return redirect('/app.html#!/login');
        }
        return redirect('/app.html#!/loginPer');
    });
    // Route::get('/external/{token}', [BeSpokeFormsController::class, 'external_link'])->name('be_spoke_forms.be_spoke_form.external_link');
});

Route::get('/external/{token}', [BeSpokeFormsController::class, 'external_link'])->name('be_spoke_forms.be_spoke_form.external_link');

// Route::get('be_spoke_form/external_link/{token}', [BeSpokeFormsController::class, 'external_link'])->name('be_spoke_forms.be_spoke_form.external_link');
Route::domain('{subdomain}.qi-tech.co.uk')->group(function () {
});

Route::get('/login', function () {
    Auth::guard('web')->logout();
    Auth::guard('user')->logout();
    return redirect('/app.html#!/login');
})->name('login');
Route::get('/signup', function () {
    return redirect('/app.html#!/signup');
})->name('signup');
Route::get('/signup/share_case/{email}', [HeadOfficeController::class, 'shared_case_signup'])->name('headOffice.shared_case_signup');

Route::post('clear-remote-access-session', [LoginController::class, 'clearRemoteAccessSession'])->name('session.clearRemoteAccess');
// This should be protected by guest or with some logic //
Route::post('/postlogin', [LoginController::class, 'login'])->name('postlogin');

Route::get('/user/logout', function (Request $request) {
    LoginController::user_logout();
    if (Auth::guard('location')->check()) {
        Auth::guard('user')->logout();
    }
    $request->session()->forget('remote_access');
    return redirect()->route('location.user_login_view');
})->name('user.logout');

Route::get('/location/logout', function () {
    Auth::guard('location')->logout();
    LoginController::user_logout();
    return redirect('/app.html#!/login');
})->name('location.logout');

Route::get('/head_office/logout', function () {
    LoginController::user_logout();
    return redirect('/app.html#!/login');
})->name('head_office.logout');
Route::get('/otp/logout', [LoginController::class, 'otp_logout'])->name('otp.logout');

//forgot password
Route::get('/forgot-password', [LoginController::class, 'forgot_password_view'])->name('forgot_password');
Route::post('/forgot-password/', [LoginController::class, 'reset_password'])->name('reset_password');
Route::get('/head_office/location/reset-password/{id}', [LoginController::class, 'reset_password_location'])->name('head_office.location.password_reset_link');


Route::get('/reset-password/{type}/{token}', [LoginController::class, 'reset_password_view'])->name('reset_password.confirm');
Route::post('/reset-password/{type}/{token}', [LoginController::class, 'reset_password_update'])->name('reset_password.update');


/// **** Admin ////
Route::get('/admin', function () {
    return view('admin.admin_login');
})->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminlogin'])->name('admin.postlogin');



/// Throtle following
Route::get('/confirm_location_details/{token}', [LocationDetailUpdateRequestsController::class, 'confirm_location_details'])->name('location.confirm_location_details');
Route::get('/confirm_location_password/{token}', [LocationPasswordUpdateRequestsController::class, 'confirm_location_password'])->name('location.confirm_location_password');
Route::get('/confirm_location_branding/{token}', [LocationBrandUpdateRequestsController::class, 'confirm_location_branding'])->name('location.confirm_location_branding');

# Standalone nearmiss 
Route::get('/report/near/miss/', [LocationController::class, 'near_miss_standalone'])->name('near_miss.standalone');
Route::post('/report/near/miss/save', [LocationController::class, 'near_miss_standalone_save'])->name('near_miss.standalone.save');
Route::any('/location/bespokeforms/form/dmds/', [BeSpokeFormsController::class, 'drugsDmd'])->name('be_spoke_forms.be_spoke_form.dmd');


// Document Upload/ Only admins can access this endpoint.
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/document/get/{id}', [DocumentController::class, 'get'])->name('document.get');
    Route::post('/document/upload', [DocumentController::class, 'upload'])->name('document.upload');
});
Route::middleware(['auth:web', 'head_office_admin', 'email_verified', 'account_suspended'])->group(function () {
    Route::get('/headoffice/view/patient/safety/alert/document/{id}', [DocumentController::class, 'view'])->name('headoffice.view.attachment');
    Route::get('/headoffice/view/patient/safety/alert/new_document/{id}', [DocumentController::class, 'new_view'])->name('headoffice.new_view.attachment');
    Route::get('/headoffice/document/get/{id}', [DocumentController::class, 'get'])->name('headoffice.document.get');
    //Route::post('/headoffice/document/upload', [DocumentController::class, 'upload'] )->name('headoffice.document.upload');
    Route::post('/headoffice/document/upload/hashed', [DocumentController::class, 'uploadHashed'])->name('headoffice.document.uploadHashed');
    Route::post('/headoffice/document/upload/audio', [DocumentController::class, 'uploadHashedAudio'])->name('headoffice.document.uploadHashedAudio');
});

Route::get('/display/log', function () {
    if ((bool) env('APP_DEBUG')) {
        $path = storage_path('logs/laravel.log');
        $mime = mime_content_type($path);
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
        ];
        echo "<pre>";
        return response()->file($path, $headers);
    }
    return abort(404);
});

Route::get('/login/{token}', [HeadOfficeController::class, 'dynamic_info'])->name('infoPage');
Route::get('/verify-OTP', [OtpController::class, 'verifyPage'])->name('verify.otp');
Route::get('/verify-OTP/resend', [OtpController::class, 'verifyResendPage'])->name('verify.otp.resend');
Route::get('/Otp-renew', [OtpController::class, 'resend_Otp'])->name('otp.renew');
Route::post('/submit-otp', [OtpController::class, 'submit_otp'])->name('submit.otp');

Route::group(['middleware' => ['web']], function () {
    if (config('app.env') == 'local') {
        Route::get('/dev-ui', function () {
            return view('dev-ui');
        });
    }
});
Route::get("/form-error", function () {
    return view('error-modal');
})->name('form-error');

Route::post('/notify-admin-error', [HeadOfficeController::class, 'notifyAdmin'])->name('api.notify-admin');

Route::get('location/bespokeforms/get_form_json_edit/{id}', [BeSpokeFormsController::class, 'getFormJsonEdit'])->name('location.be_spoke_forms_templates.get_form_json_edit');
Route::get('head_office/bespokeforms/get_form_json_edit/{id}', [BeSpokeFormsController::class, 'getFormJsonEdit'])->name('head_office.be_spoke_forms_templates.get_form_json_edit');



Route::get('/t-login/{token}', [LoginController::class, 'temporaryLogin'])->name('temporary-login');
