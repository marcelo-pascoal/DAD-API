<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VcardController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\TransactionController;

Route::post('login', [AuthController::class, 'login']);

// Registration
Route::post('users', [UserController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show'])
        ->middleware('can:view,user');
    Route::put('users/{user}', [UserController::class, 'update'])
        ->middleware('can:update,user');
    Route::patch('users/{user}/password', [UserController::class, 'update_password'])
        ->middleware('can:updatePassword,user');


    Route::apiResource('vcards', VcardController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('transactions', TransactionController::class);

    Route::get('categories', [CategoryController::class, 'index']);

    Route::middleware(['check.user.admin'])->group(function () {
        Route::delete('categories/{category}', [CategoryController::class, 'destroyDefault']);
    });
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
});


Route::post('vcards', [VcardController::class, 'store']);
Route::patch('vcards/{vcard}/blocked', [VcardController::class, 'updateBlocked']);
