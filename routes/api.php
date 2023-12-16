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

    Route::get('vcards', [VcardController::class, 'index']);
    Route::post('vcards', [VcardController::class, 'store']);
    Route::put('vcards/{vcard}', [VcardController::class, 'update']);
    Route::get('vcards/{vcard}', [VcardController::class, 'show']);
    Route::delete('vcards/{vcard}', [VcardController::class, 'destroy']);
    Route::patch('vcards/{vcard}/confirmation_code', [VcardController::class, 'update_confirmation_code']);
    Route::put('update/vcards/{vcard}', [VcardController::class, 'update_admin']);
    Route::patch('vcards/{vcard}/blocked', [VcardController::class, 'updateBlocked']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    Route::put('categories/default/{category}', [CategoryController::class, 'updateDefault']);
    Route::delete('categories/default/{category}', [CategoryController::class, 'destroyDefault']);

    Route::get('/transactions/all', [TransactionController::class, 'allTransactions']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::put('transactions/{transaction}', [CategoryController::class, 'update']);
});

Route::post('vcards', [VcardController::class, 'store']);
