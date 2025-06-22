<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Rider\RiderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth'])->group(function () {
    // order Routes
    Route::get('dashboard', [OrderController::class, 'index'])->name('dashboard');
    Route::get('orders',[OrderController::class, 'create'])->name('order.create');
    Route::post('orders', [OrderController::class, 'store'])->name('order.store');
    Route::get('orders/{orderId}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::put('orders/{orderId}', [OrderController::class, 'updateOrder'])->name('order.update');
    Route::get('orders/{orderId}', [OrderController::class, 'show'])->name('order.show');
    Route::delete('orders/{orderId}', [OrderController::class, 'destroy'])->name('order.destroy');
    
    // Rider Routes
    Route::get('riders', [RiderController::class, 'index'])->name('rider.index');
    Route::get('riders/{riderId}/edit', [RiderController::class, 'edit'])->name('rider.edit');
    Route::put('riders/{riderId}', [RiderController::class, 'update'])->name('rider.update');
    Route::get('riders/{riderId}', [RiderController::class, 'show'])->name('rider.show');
    Route::get('rider', [RiderController::class, 'create'])->name('rider.create');
    Route::post('riders', [RiderController::class, 'store'])->name('rider.store');
    Route::delete('riders/{riderId}', [RiderController::class, 'destroy'])->name('rider.destroy');
   
    // Customer Routes
    Route::get('customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer',[CustomerController::class, 'create'])->name('customer.create');
    Route::post('customers', [CustomerController::class,'store'])->name('customer.store');
    Route::get('customers/{customerId}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customers/{customerId}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('customers/{customerId}', [CustomerController::class, 'show'])->name('customer.show');
    Route::delete('customers/{customerId}', [CustomerController::class, 'destroy'])->name('customer.destroy');
   
    // Feedback Routes
    Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedback.index');
});

