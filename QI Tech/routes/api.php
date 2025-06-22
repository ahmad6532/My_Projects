<?php

use App\Http\Controllers\GdprController;
use App\Http\Controllers\HeadOffice\HeadOfficeController;
use App\Http\Controllers\Location\Forms\BeSpokeFormsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\Auth\LocationSignupController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Api\Http\Controllers\Api\Api\UsersController;
use App\Http\Controllers\Api\Auth\UsersSignupController;
use App\Api\Http\Controllers\Api\Api\HeadOfficeRequestsController;
use App\Http\Controllers\Api\Auth\HeadOfficeSignupController;
use App\Http\Controllers\Api\LfpseDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum', 'verified')->get('/user', function (Request $request) {
     return $request->user();
});

Route::group([
     'middleware' => 'auth:sanctum',
], function () {

     /*
     Route::group([
          'prefix' => 'locations',
     ], function () {
          Route::get('/', [LocationsController::class, 'index'])
               ->name('api.locations.location.index');
          Route::get('/show/{location}',[LocationsController::class, 'show'])
               ->name('api.locations.location.show')->where('id', '[0-9]+');
          Route::post('/', [LocationsController::class, 'store'])
               ->name('api.locations.location.store');
          Route::put('location/{location}', [LocationsController::class, 'update'])
               ->name('api.locations.location.update')->where('id', '[0-9]+');
          Route::delete('/location/{location}',[LocationsController::class, 'destroy'])
               ->name('api.locations.location.destroy')->where('id', '[0-9]+');
     });
     
     
     Route::group([
          'prefix' => 'users',
     ], function () {
          Route::get('/', [Api\UsersController::class, 'index'])
               ->name('api.users.user.index');
          Route::get('/show/{user}',[Api\UsersController::class, 'show'])
               ->name('api.users.user.show')->where('id', '[0-9]+');
          Route::post('/', [Api\UsersController::class, 'store'])
               ->name('api.users.user.store');    
          Route::put('user/{user}', [Api\UsersController::class, 'update'])
               ->name('api.users.user.update')->where('id', '[0-9]+');
          Route::delete('/user/{user}',[Api\UsersController::class, 'destroy'])
               ->name('api.users.user.destroy')->where('id', '[0-9]+');
     });
     
     Route::group([
          'prefix' => 'head_office_requests',
     ], function () {
          Route::get('/', [Api\HeadOfficeRequestsController::class, 'index'])
               ->name('api.head_office_requests.head_office_request.index');
          Route::get('/show/{headOfficeRequest}',[Api\HeadOfficeRequestsController::class, 'show'])
               ->name('api.head_office_requests.head_office_request.show')->where('id', '[0-9]+');
          Route::post('/', [Api\HeadOfficeRequestsController::class, 'store'])
               ->name('api.head_office_requests.head_office_request.store');    
          Route::put('head_office_request/{headOfficeRequest}', [Api\HeadOfficeRequestsController::class, 'update'])
               ->name('api.head_office_requests.head_office_request.update')->where('id', '[0-9]+');
          Route::delete('/head_office_request/{headOfficeRequest}',[Api\HeadOfficeRequestsController::class, 'destroy'])
               ->name('api.head_office_requests.head_office_request.destroy')->where('id', '[0-9]+');
     });
     */

});

// Single
Route::group([
     'prefix' => 'location',
], function () {

     Route::get('/email_exists/{email}', [LocationSignupController::class, 'email_exists'])
          ->name('api.locations.location.email_exists')->middleware('throttle:7,5');

     Route::get('/username_exists/{username}', [LocationSignupController::class, 'username_exists'])
          ->name('api.locations.location.username_exists')->middleware('throttle:7,5');
});

Route::get('/signup/location/{type}/{value}', [LocationSignupController::class, 'get_location_details'])
     ->name('api.locations.location.details');

Route::get('/signup/user/{type}/{value}', [LocationSignupController::class, 'get_user_details'])
     ->name('api.users.user.details')->middleware('throttle:7,5');



Route::group([
     'prefix' => 'user',
], function () {
     Route::post('/register', [UsersSignupController::class, 'register'])
          ->name('api.users.user.register')->middleware('throttle:5,1');
     Route::get('/email_exists/{email}', [UsersSignupController::class, 'email_exists'])
          ->name('api.users.user.email_exists')->middleware('throttle:7,5');
});

Route::group([
     'prefix' => 'head_office',
], function () {
     Route::post('/request', [HeadOfficeSignupController::class, 'register'])
          ->name('api.head-office.request.register')->middleware('throttle:7,5');

     Route::get('/email_exists/{email}', [HeadOfficeSignupController::class, 'email_exists'])
     ->name('api.users.user.email_exists')->middleware('throttle:7,1');
     Route::get('/ods_check/{code}', [LfpseDataController::class, 'ods_check'])
     ->name('api.users.user.ods_check');

     });


// Login
Route::post('/login', [LoginController::class, 'login'])->name('api.login');

//  PersonlizedLogin 
Route::post('/login/token', [HeadOfficeController::class, 'theme_data'])->name('api.login.token');

Route::get('/gdpr_tags/options/{id}', [GdprController::class, 'get_options'])->name('api.gdpr_tags.options');

Route::get('/location_groups/{id}', [LfpseDataController::class, 'location_groups'])
          ->name('api.location_groups');
Route::get('/all_locations/{id}', [LfpseDataController::class, 'all_locations'])
          ->name('api.locations');
Route::get('/check_submission/{email}', [LfpseDataController::class, 'check_submission'])
          ->name('api.check_submission')->middleware('throttle:7,5');
// public for lfpse //
Route::group([
     'prefix' => 'lfpse',
], function () {
     Route::get('/options/{collection}/{version}', [LfpseDataController::class, 'get_options'])
          ->name('api.lfpse_data.options');
          Route::get('/dmd', [LfpseDataController::class, 'get_dmd_options'])
          ->name('api.lfpse_data.dmd_options');
          Route::get('/dmd_vtm', [LfpseDataController::class, 'get_dmd_options_vtm'])
          ->name('api.lfpse_data.dmd_options');
          Route::get('/dmd_vmp', [LfpseDataController::class, 'get_dmd_options_vmp'])
          ->name('api.lfpse_data.dmd_options');
          Route::get('/dmd_vmp_new', [LfpseDataController::class, 'get_dmd_options_vmp_new'])
          ->name('api.lfpse_data.dmd_options_new');
    

});

Route::get('get_ods_codes/postal/{code}', [LfpseDataController::class, 'get_ods_codes'])->name('api.get_ods_codes');
Route::get('get_ods_details/ods/{code}', [LfpseDataController::class, 'get_ods_details'])->name('api.get_ods_details');

