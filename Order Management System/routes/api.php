<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Rider\RiderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Auth Routes
Route::post('signin', [AuthController::class, 'login']);
// Rider SignUp Route
Route::post('rider/signup', [RiderController::class, 'store']);
// customer SignUp Route
Route::post('customer/signup', [CustomerController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function () {


    // Group of Customer Routes
    Route::middleware(['customerCheck'])->group(function () {
        // update customer profile
        Route::post('updateProfile', [CustomerController::class, 'update']);
        // customer view his/her single order
        Route::get('order/{orderId}', [OrderController::class, 'viewOrder']);
        // all orders of single customer
        Route::get('customerAllOrder', [OrderController::class, 'customerAllOrder']);
        // edit order by customer till PENDING status
        Route::put('order/{orderId}/edit', [OrderController::class, 'updateOrder']);
        // Feedback Route
        Route::post('order/feedback', [FeedbackController::class, 'feedback']);
    });



    // Group of Ridere Routes
    Route::middleware(['riderCheck'])->group(function () {
        // list of all user orders at a time
        Route::get('allUserOrder/{status}', [OrderController::class, 'allUserOrder']);
        // Update Order Status
        Route::put('order/updateOrder/{orderId}', [OrderController::class, 'updateOrderStatus']);

    });
 

    //   logout Route
    Route::post('signout', [AuthController::class, 'logout']);
});
