<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\Auth\AuthController;
use App\Http\Controllers\Management\Auth\UserController;
use App\Http\Controllers\Management\School\SchoolController;
use App\Http\Controllers\Management\School\SchoolCategoryController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('management')->group(function () {

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('delete/{id}', [UserController::class, 'destroy']);
        });

        // general routes
        Route::post('set_up_school', [SchoolController::class, 'setup_school'])->
            name('set_up_school');

        // super admin routes
        Route::group(
            [ 'middleware' => ['superadmin']],
            function () {

                Route::prefix('school')->group(function () {
                    Route::get('/', [SchoolController::class, 'index']);
                    Route::get('/{id}', [SchoolController::class, 'show']);
                    // Route::post('/', [SchoolController::class, 'store']);
                     Route::put('/{id}', [SchoolController::class, 'update']);
                    Route::delete('delete/{id}', [SchoolController::class, 'destroy']);
                });

            }
        );
        // school admin routes

        Route::group(['prefix' => '{school_slug}', 'middleware' => ['school']],
            function () {

            Route::prefix('school')->group(function () {
                Route::get('/{id}', [SchoolController::class, 'show']);
                Route::put('/{id}', [SchoolController::class, 'update']);

            });

            // school category routes
            Route::prefix('school_category')->group(function () {
                Route::get('/', [SchoolCategoryController::class, 'index']);
                Route::get('/{id}', [SchoolCategoryController::class, 'show']);
                Route::post('/', [SchoolCategoryController::class, 'store']);
                Route::put('/{id}', [SchoolCategoryController::class, 'update']);
                Route::delete('delete/{id}', [SchoolCategoryController::class, 'destroy']);
            });
        });

    });



});
