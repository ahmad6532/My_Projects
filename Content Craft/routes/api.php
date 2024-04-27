<?php

use App\Http\Controllers\Api\User\ArticleController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\PlanController;
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




Route::post('signin', [HomeController::class, 'signin']);
Route::post('signup', [HomeController::class, 'signUp']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['userRole'])->group(function () {
        // Articles Routes
        Route::get('articles', [ArticleController::class, 'show']);
        Route::post('articles', [ArticleController::class, 'store']);
        Route::get('usersArticle/{articleId}', [ArticleController::class, 'fetchArticle']);
        Route::get('articles/{articleId}/edit', [ArticleController::class, 'edit']);
        Route::put('articles/{articleId}', [ArticleController::class, 'update']);
        Route::get('allUsersArticles ', [ArticleController::class, 'getAll']);
        Route::delete('articles/{articleId}', [ArticleController::class, 'destroy']);

        // Plan Resource
        Route::get('allPlans', [PlanController::class, 'allPlans']);
        Route::post('purchase', [PlanController::class, 'purchase']);
    });

    // logout Route
    Route::post('signout', [HomeController::class, 'logout']);
});
