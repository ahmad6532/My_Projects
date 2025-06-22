<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\WebsiteContentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//================================Route for cron job===========================
Route::get('send_comm_email', [CommunicationController::class, 'send_comm_email']);
Route::get('send_comm_sms', [CommunicationController::class, 'send_comm_sms']);
Route::get('fleet_list_without_auth', [FleetController::class, 'fleet_list_without_auth']);
Route::get('check_doc_expiry', [AdminController::class, 'check_doc_expiry']);



//================================  Admin Module Routes==========================
Route::prefix('admin')->group(function () {
    Route::get('get_website_branding', [AdminController::class ,'get_website_branding']);
    Route::get('/download-and-delete/{fileName}', [AdminController::class ,'downloadAndDelete'])->name('downloadAndDelete');

    Route::controller(AdminController::class)->middleware('auth:api')->group(function () {
        Route::post('add_fare', 'add_fare')->middleware(['permission:write-fare|view-fare', 'auth:api']);
        Route::get('approved_company', 'approved_company')->middleware('permission:write-admin-approval', 'auth:api');
        Route::get('approved_user', 'approved_user')->middleware('permission:write-admin-approval', 'auth:api');
        Route::get('approved_driver', 'approved_driver')->middleware('permission:write-admin-approval', 'auth:api');
        Route::post('destinations', 'destinations');
        Route::delete('destination_delete/{id}', 'destination_delete');
        Route::get('approved_company_quote', 'approved_company_quote')->middleware('permission:write-admin-approval', 'auth:api');
        Route::get('approved_changed_request', 'approved_changed_request')->middleware('permission:write-admin-approval', 'auth:api');
        Route::delete('company/{id}', 'company_delete')->middleware('permission:write-admin-approval', 'auth:api');

        //======================Listing Dispatch Booking=======================
        Route::get('manual_dispatch_booking_listing', 'manual_dispatch_booking_listing')->middleware('permission:write-admin-approval', 'auth:api');
        //======================Add Dispatch Booking==========================
        Route::post('manual_dispatch_booking', 'manual_dispatch_booking')->middleware('permission:write-admin-approval', 'auth:api');
        //======================create booking and dispatch =======================
        Route::post('admin_create_booking', 'admin_create_booking')->middleware('permission:write-admin-approval', 'auth:api');
        //====================Documents Module============================
        Route::get('driver_documents_listing', 'driver_documents_listing')->middleware('permission:view-documents', 'auth:api');
        // Route::get('approved_driver_documents', 'approved_driver_documents');
        Route::get('company_documents_listing', 'company_documents_listing')->middleware('permission:view-documents', 'auth:api');
        // Route::get('approved_company_documents', 'approved_company_documents');
        Route::get('fleet_documents_listing', 'fleet_documents_listing')->middleware('permission:view-documents', 'auth:api');
        // Route::get('approved_fleet_documents', 'approved_fleet_documents');
        Route::get('approved_document', 'approved_document')->middleware('permission:write-admin-approval', 'auth:api');
        Route::get('search_documents', 'search_documents')->middleware('permission:view-documents', 'auth:api');
        //================= Driver Module============
        Route::get('driver_listing', 'driver_listing');
        Route::get('search_drivers', 'search_drivers');
        //================= Company Module============
        Route::get('company_listing', 'company_listing');
        Route::get('company_listing_by_id', 'company_listing_by_id');
        Route::get('search_company', 'search_company');
        //================= Customer Module============
        Route::get('customer_listing', 'customer_listing');
        Route::get('search_customer', 'search_customer');
        //==================Payment Listing==============
        Route::get('users_payment_listing', 'users_payment_listing');
        //==================Download Payment Listing==============
        Route::get('download_payment_listing', 'download_payment_listing');
        //==================Feedback Listing==============
        Route::get('users_feedback_listing', 'users_feedback_listing');
        //==================Affliate Api Listing==============
        Route::get('affiliate_api_listing', 'affiliate_api_listing');
        //======================Celander=======================
        Route::get('admin_celander', 'admin_celander')->middleware('permission:view-calendar', 'auth:api');
        // //==================Dashboard Api Routes=====================
        // Route::get('dashboard_stats', 'dashboard_stats');
        //======================Driver/Company Listing=======================
        Route::get('company_driver_listing', 'company_driver_listing');

        //======================FAQS==================================
        Route::post('section_name', 'section_name');
        Route::get('delete_section', 'delete_section');


        //======= Search Api's of Company Driver and Customer name ============
        Route::get('booking_search_admin', 'booking_search_admin');


        //====================Setting Module=============================
        //=====================Fleet Types =============================
        Route::post('manage_fleet_types', 'manage_fleet_types');
        Route::get('get_fleet_types', 'get_fleet_types');
        Route::get('delete_fleet_type', 'delete_fleet_type');


        //======================SMTP Credetials=========================
        Route::get('smtp', 'smtp');
        Route::post('smtp_update', 'smtp_update');

        //======================SMS Credetials=========================
        Route::get('sms', 'sms');
        Route::post('sms_update', 'sms_update');

        //==================Manage Stripe Credentials ==================
        Route::get('stripe_data', 'stripe_data');
        Route::post('stripe_update', 'stripe_update');

        //===================Website Branding Module=====================
        Route::post('website_branding', 'website_branding');


        //===================Bank Transfer Module=====================
        Route::post('payment_accounts', 'payment_accounts');
        Route::get('payment_accounts_list', 'payment_accounts_list');
        Route::get('payment_account_get_by_id', 'payment_account_get_by_id');
        Route::get('search_accounts', 'search_accounts');


        //===================Home Section Module=====================
        Route::post('home_section', 'home_section');


        //=========================Business Page===============================
        Route::post('business_page', 'business_page');


        //===================About Section Module=====================
        Route::post('about_content', 'about_content');

        //=====================Testimonials Module=============================
        Route::post('testimonials_content', 'testimonials_content');
        Route::get('delete_testimonials', 'delete_testimonials');


        //=====================Contact Page Module=============================
        Route::post('contact_page', 'contact_page');


        //=====================Become a Driver Page Module=============================
        Route::post('become_driver_page', 'become_driver_page');

        //=====================Become a Operator Page Module=============================
        Route::post('become_operator_page', 'become_operator_page');

        //====================Notification Management==========================
        Route::get('get_notification_content', 'get_notification_content');
        Route::post('update_notification_content', 'update_notification_content');
        Route::get('search_notification_type', 'search_notification_type');


        //==========================Admin Profile=============================
        Route::get('get_data_admin_profile', 'get_data_admin_profile');
        Route::get('update_password_admin', 'update_password_admin');
        Route::post('update_admin_profile', 'update_admin_profile');


        //==========================Coupons=============================
        Route::get('list_coupon', 'list_coupon');
        Route::post('save_update_coupon', 'save_update_coupon');
        Route::get('edit_coupon', 'edit_coupon');
        Route::get('delete_coupon', 'delete_coupon');


        //=============================Roles and Permissions=============================
        //Roles
        // Route::post('add-role', [RolesPermissionsController::class, 'addRole'])->middleware('permission:write-role&permission', 'auth:api');
        Route::post('add-role', [RolesPermissionsController::class, 'addRole']);
        // Route::get('get-role', [RolesPermissionsController::class, 'getRole'])->middleware('permission:view-role&permission', 'auth:api');
        Route::get('get-role', [RolesPermissionsController::class, 'getRole']);
        // Route::get('get-role-operator', [RolesPermissionsController::class, 'getRoleOperator'])->middleware('permission:view-role&permission', 'auth:api');
        Route::get('get-role-operator', [RolesPermissionsController::class, 'getRoleOperator']);

        // Route::get('get-role/{id}', [RolesPermissionsController::class, 'showRole'])->middleware('permission:view-role&permission', 'auth:api');
        Route::get('get-role/{id}', [RolesPermissionsController::class, 'showRole']);

        // Route::delete('delete-role/{id}', [RolesPermissionsController::class, 'deleteRole'])->middleware('permission:delete-role&permission', 'auth:api');
        Route::delete('delete-role/{id}', [RolesPermissionsController::class, 'deleteRole']);


        //=================================Assign Permisson to roles=================================
        // Route::get('all-permissions', [RolesPermissionsController::class, 'getPermissions'])->middleware('permission:write-role&permission', 'auth:api');
        Route::get('all-permissions', [RolesPermissionsController::class, 'getPermissions']);

        Route::post('save-roll-permissions', [RolesPermissionsController::class, 'saveRollPermissions']);

        // Route::get('edit-roll-permissions', [RolesPermissionsController::class, 'editRollPermissions'])->middleware('permission:view-role&permission', 'auth:api');
        Route::get('edit-roll-permissions', [RolesPermissionsController::class, 'editRollPermissions']);

        // Route::post('update-roll-permissions', [RolesPermissionsController::class, 'updateRollPermissions'])->middleware('permission:write-role&permission', 'auth:api');
        Route::post('update-roll-permissions', [RolesPermissionsController::class, 'updateRollPermissions']);
    });
});


//================================  Admin Module with out Auth Routes==========================
Route::prefix('admin')->group(function () {
    Route::controller(AdminController::class)->group(function () {

        //===================Home Section Module=====================
        Route::get('get_home_section', 'get_home_section');


        //===================About Section Module=====================
        Route::get('get_about_content', 'get_about_content');

        //=====================Testimonials Module=============================
        Route::get('get_testimonials_content', 'get_testimonials_content');
        Route::get('testimonials_content_by_id', 'testimonials_content_by_id');
        Route::get('search_testimonials', 'search_testimonials');

        //=========================Business Page===============================
        Route::get('get_business_page', 'get_business_page');

        //=====================Contact Page Module=============================
        Route::get('get_contact_page', 'get_contact_page');

        //=====================Become a Driver Page Module=============================
        Route::get('get_become_driver_page', 'get_become_driver_page');

        //=====================Become a Operator Page Module=============================
        Route::get('get_become_operator_page', 'get_become_operator_page');
    });
});


//======================== Login/Sign up Module Routes========================
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout_user', 'logout_user');
    Route::post('refresh', 'refresh');
    Route::get('profile', 'profile');
    Route::post('edit_profile', 'edit_profile');
});

//=================================Forgot Password======================================
Route::post('forgot_password', [ForgotPasswordController::class, 'forgot_password']);
Route::post('update_password', [ForgotPasswordController::class, 'update_password']);
Route::post('check_otp', [ForgotPasswordController::class, 'check_otp']);


//======================== Driver Sign up Module Routes========================
Route::controller(DriverController::class)->group(function () {
    Route::post('driver_signup', 'driver_signup');
    Route::get('fleet_types', 'fleet_types');
    Route::get('fleet_manufacturer', 'fleet_manufacturer');
    Route::get('fleet_models', 'fleet_models');
    Route::get('fleet_classes', 'fleet_classes');
});

//======================== Company Registered Module Routes========================
Route::controller(CompanyController::class)->group(function () {
    // Route::post('company', 'add_company');
    // Route::put('company', 'edit_company');
    Route::post('company/{company_id?}', 'company'); // Create and Edit
    Route::post('documents/{document_id?}', 'documents'); // Create and Edit
    Route::get('documents_list/{company_id?}', 'documents_list');
    // Route::post('faq' , 'faq');

});

//======================== Website Content Module Routes========================
Route::controller(WebsiteContentController::class)->group(function () {
    Route::post('contact_us', 'contact_us');
    Route::post('business_model', 'business_model');
    Route::get('destinations_list', 'destinations_list');
    Route::get('destinations_get_by_id', 'destinations_get_by_id');
    Route::post('affiliate_api_partner', 'affiliate_api_partner');
    Route::post('news_letter_api', 'news_letter_api');
    // Route::post('website_branding', 'website_branding');

});

//======================== Website links to show in footer ========================
// Route::get('get_web_links', [AdminController::class, 'get_web_links']);



//======================== Website Booking Module Routes========================
Route::prefix('user/booking')->group(function () {
    Route::controller(BookingController::class)->group(function () {
        Route::get('calculate_fare', 'calculate_fare');
        Route::post('create_booking', 'create_booking');
        Route::delete('delete_booking/{id}', 'delete_booking')->middleware('auth:api');
        Route::get('booking/{id}', 'booking'); // Route and Model Binding Example Booking data directly get in function parameter
        Route::get('booking_history', 'booking_history')->middleware('auth:api');
        Route::get('track_booking', 'track_booking');
        Route::post('booking_feedback', 'booking_feedback');
    });
});

//============================== Payment Module Routes============================
Route::prefix('user/payments')->group(function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::post('cash', 'cash');
    });
});

Route::get('/payment-success', [BookingController::class, 'payment_success'])->name('payment.success');

Route::get('/admin/payment-success', [AdminController::class, 'payment_success'])->name('payment.success.admin');



Route::get('/payment-cancel', function () {
    return view('payment_failed');
})->name('payment.cancel');
Route::post('/stripe-webhook', [WebhookController::class, 'handleWebhook']); // Stripe Webhook Route
//=======================Stripe Controller ================================
Route::controller(StripePaymentController::class)->group(function () {
    Route::post('stripe', 'stripePost')->name('stripe.post');
});




//======================== Company Module Routes========================
Route::prefix('company')->middleware('auth:api')->group(function () {

    //========================Fleet Module=============================
    Route::controller(FleetController::class)->group(function () {
        Route::post('/fleet/create_fleet', 'create_fleet')->middleware('permission:write-fleet', 'auth:api');
        Route::get('/fleet/fleet_list', 'fleet_list')->middleware('permission:view-fleet', 'auth:api');
        Route::get('/fleet/fleet_list_dropdown', 'fleet_list_dropdown')->middleware('permission:view-fleet', 'auth:api');
        Route::delete('fleet/delete_fleet/{id}', 'delete_fleet')->middleware('permission:delete-fleet', 'auth:api');
        Route::get('/fleet/search_fleets', 'search_fleets')->middleware('permission:view-fleet', 'auth:api');
    });

    Route::controller(CompanyController::class)->group(function () {

        Route::get('qoutes_list', 'qoutes_list')->middleware('permission:view-quotes', 'auth:api'); //also used for admin as well
        Route::get('quotes_against_booking', 'quotes_against_booking')->middleware('permission:view-quotes', 'auth:api'); //also used for admin as well
        Route::get('company_request_against_booking', 'company_request_against_booking')->middleware('permission:view-quotes', 'auth:api');
        Route::get('driver_request_against_booking', 'driver_request_against_booking')->middleware('permission:view-quotes', 'auth:api');
        Route::get('company_change_request_against_booking', 'company_change_request_against_booking');
        Route::get('cancel_change_request_against_booking', 'cancel_change_request_against_booking');
        Route::get('company_bookings', 'company_bookings')->middleware('permission:view-bookings', 'auth:api');
        Route::get('advance_search_booking', 'advance_search_booking')->middleware('permission:view-bookings', 'auth:api');
        Route::post('/quote/create_quote/{quote_id?}', 'create_quote');
        Route::get('mark_unavailable', 'mark_unavailable')->middleware('permission:view-quotes', 'auth:api');
        Route::get('advance_search_quote', 'advance_search_quote')->middleware('permission:view-quotes', 'auth:api');
        Route::get('payment_list_against_company', 'payment_list_against_company')->middleware('permission:view-payment', 'auth:api');
        Route::get('feedback_against_company', 'feedback_against_company')->middleware('permission:view-customer-feedback', 'auth:api');
        Route::get('overall_feedback_against_company', 'overall_feedback_against_company')->middleware('permission:view-customer-feedback', 'auth:api');
        Route::get('document_remove', 'document_remove')->middleware('permission:delete-document', 'auth:api');
        Route::get('company_celander', 'company_celander')->middleware('permission:view-calendar', 'auth:api');
        Route::get('company_profile', 'company_profile');
        Route::post('/details/edit_company_profile', 'edit_company_profile');
        Route::post('/manage/company_user', 'company_user');
        Route::get('/list/company_user', 'list_company_user');
        Route::get('delete_company_user', 'delete_company_user');
        Route::get('dashboard_stats', 'dashboard_stats')->middleware('permission:view-dashboard', 'auth:api'); //also used at admin dashboard
        Route::get('fleet_dashboard', 'fleet_dashboard')->middleware('permission:view-dashboard', 'auth:api');
        Route::get('quotes_history', 'quotes_history')->middleware('permission:view-quotes', 'auth:api');
        Route::get('booking_history', 'booking_history')->middleware('permission:view-bookings', 'auth:api');
        Route::post('/manage/faq', 'faq');
        // Route::get('/list/faq' , 'faq_listing');
        Route::get('/list/sec_name', 'sec_name_listing');
        Route::post('contact/company_contact_us', 'company_contact_us');
        Route::get('/list/notice_board', 'notice_board')->middleware('permission:view-dashboard', 'auth:api');
        Route::get('/quote/quote_get_against_id', 'quote_get_against_id')->middleware('permission:view-quotes', 'auth:api');
        Route::get('/change_payment_status', 'change_payment_status');
        Route::post('/password/update_company_password', 'update_company_password');
        Route::get('/cancel/mark_unavailable_booking', 'mark_unavailable_booking');
    });
});
//============without auth route===========
Route::post('company/quote/create_quote/{quote_id?}', [CompanyController::class, 'create_quote']);
Route::get('company/list/sec_name', [CompanyController::class, 'sec_name_listing']);
