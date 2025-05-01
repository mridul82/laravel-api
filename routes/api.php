<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

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

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password reset routes
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User profile routes
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::put('/password', [UserController::class, 'updatePassword']);

    // Role management routes
    Route::group(['middleware' => ['permission:role-list']], function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
    });

    Route::group(['middleware' => ['permission:role-create']], function () {
        Route::post('/roles', [RoleController::class, 'store']);
    });

    Route::group(['middleware' => ['permission:role-edit']], function () {
        Route::put('/roles/{id}', [RoleController::class, 'update']);
    });

    Route::group(['middleware' => ['permission:role-delete']], function () {
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });

    // User management routes
    Route::group(['middleware' => ['permission:user-list']], function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });

    Route::group(['middleware' => ['permission:user-create']], function () {
        Route::post('/users', [UserController::class, 'store']);
    });

    Route::group(['middleware' => ['permission:user-edit']], function () {
        Route::put('/users/{id}', [UserController::class, 'update']);
    });

    Route::group(['middleware' => ['permission:user-delete']], function () {
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
