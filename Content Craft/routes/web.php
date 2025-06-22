<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Manager\HomeController as ManagerHomeController;
use App\Http\Controllers\Manager\UserController;
use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\PlanController;
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

    // Dashboard
    Route::get('dashboard', [HomeController::class, 'index']);


    // The routes only admin can access
    Route::middleware(['adminRole'])->group(function () {
        Route::get('admin/dashboard', [HomeController::class, 'index'])->name('admin.index');
        Route::get('admin/{adminId}', [HomeController::class, 'show'])->name('admin.show');
        Route::get('admin/{adminId}/edit', [HomeController::class, 'edit'])->name('admin.edit');
        Route::put('admin/{adminId}', [HomeController::class, 'update'])->name('admin.update');
        Route::post('chart', [HomeController::class, 'barChart'])->name('admin.chart');
        // Manager Routes
        Route::get('allManagers', [ManagerController::class, 'allManagers'])->name('admin.allManagers');
        Route::get('manager', [ManagerController::class, 'create'])->name('manager.create');
        Route::post('manager', [ManagerController::class, 'store'])->name('manager.store');
        Route::get('manager/{managerId}', [ManagerController::class, 'showManager'])->name('admin.showManager');
        Route::get('manager/{managerId}/edit', [ManagerController::class, 'editManager'])->name('admin.editManager');
        Route::put('manager/{managerId}', [ManagerController::class, 'updateManager'])->name('admin.updateManager');
        Route::delete('manager/{managerId}', [ManagerController::class, 'destroy'])->name('admin.deleteManager');
        // Block UnBlock manager
        Route::put('manager/blockUnblock/{managerId}', [ManagerController::class, 'blockUnblockManager'])->name('blockUnblock');
    });


    // The routes only manager can access
    Route::middleware(['managerRole'])->group(function () {
        Route::get('managers/dashboard', [ManagerHomeController::class, 'index'])->name('manager.index');
        Route::get('managers/{managerId}', [ManagerHomeController::class, 'show'])->name('manager.show');
        Route::get('managers/{managerId}/edit', [ManagerHomeController::class, 'edit'])->name('manager.edit');
        Route::put('managers/{managerId}', [ManagerHomeController::class, 'update'])->name('manager.update');
        //users routes
        Route::get('allUsers', [UserController::class, 'allUsers'])->name('manager.allUsers');
        Route::get('users', [UserController::class, 'create'])->name('user.create');
        Route::post('users', [UserController::class, 'store'])->name('user.store');
        Route::get('user/{userId}/edit', [UserController::class, 'editUser'])->name('user.editUser');
        Route::put('user/{userId}', [UserController::class, 'updateUser'])->name('user.updateUser');
        Route::get('user/{userId}', [UserController::class, 'showUser'])->name('user.showUser');
        Route::put('blockUnblock/user/{userId}', [UserController::class, 'blockUnblockUser'])->name('blockUnblockUser');
        Route::delete('users/{userId}', [UserController::class, 'destroy'])->name('user.delete');
    });



    // The routes only User can access
    Route::middleware(['userRole'])->group(function () {
        // Articles Routes
        Route::get('users/dashboard', [UserHomeController::class, 'index'])->name('article.index');
        Route::get('users/{userId}/edit', [UserHomeController::class, 'edit'])->name('user.edit');
        Route::put('users/{userId}', [UserHomeController::class, 'update'])->name('user.update');
        Route::get('users/{userId}', [UserHomeController::class, 'show'])->name('user.show');
        Route::get('article', [ArticleController::class, 'create'])->name('article.create');
        Route::post('articles', [ArticleController::class, 'store'])->name('article.store');
        Route::get('articles/{userId}', [ArticleController::class, 'show'])->name('article.show');
        Route::get('articles/{articleId}/edit', [ArticleController::class, 'edit'])->name('article.edit');
        Route::put('articles/{articleId}', [ArticleController::class, 'update'])->name('article.update');
        Route::delete('articles/{articleId}', [ArticleController::class, 'destroy'])->name('article.delete');
        // like articles
        Route::post('article/like/{articleId}', [ArticleController::class, 'likeArticle'])->name('article.like');

        // Plan Routes
        Route::get('allPlans', [PlanController::class, 'allPlans'])->name('plan.index');
        Route::get('plan/{planId}', [PlanController::class, 'singlePlan'])->name('plan.show');
        Route::get('subscribed_plan', [PlanController::class, 'showUserPlan'])->name('plan.userPlan');
        Route::get('receipt/{userId}', [PlanController::class, 'showReceipt'])->name('plan.receipt');
        Route::post('purchase', [PlanController::class, 'purchase'])->name('plan.purchase');
    });
});
