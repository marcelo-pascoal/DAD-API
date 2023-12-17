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
    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->middleware('can:delete,user');



    Route::get('vcards', [VcardController::class, 'index'])
        ->middleware('can:admin,App\Models\Vcard');
    Route::put('update/vcards/{vcard}', [VcardController::class, 'update_admin'])
        ->middleware('can:admin,App\Models\Vcard');
    Route::patch('vcards/{vcard}/blocked', [VcardController::class, 'updateBlocked'])
        ->middleware('can:admin,App\Models\Vcard');
    Route::get('vcards/{vcard}', [VcardController::class, 'show'])
        ->middleware('can:view,vcard');
    Route::put('vcards/{vcard}', [VcardController::class, 'update'])
        ->middleware('can:update,vcard');
    Route::patch('vcards/{vcard}/confirmation_code', [VcardController::class, 'update_confirmation_code'])
        ->middleware('can:update,vcard');
    Route::delete('vcards/{vcard}', [VcardController::class, 'destroy'])
        ->middleware('can:delete,vcard');

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);

    Route::put('categories/{category}', [CategoryController::class, 'update'])
        ->middleware('can:update,category');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('can:delete,category');

    Route::put('categories/default/{category}', [CategoryController::class, 'updateDefault'])
        ->middleware('can:admin,App\Models\Category');
    Route::delete('categories/default/{category}', [CategoryController::class, 'destroyDefault'])
        ->middleware('can:admin,App\Models\Category');

    Route::get('/transactions/all', [TransactionController::class, 'allTransactions'])
        ->middleware('can:admin,App\Models\Transaction');
    Route::get('transactions', [TransactionController::class, 'index'])
        ->middleware('can:check,App\Models\Transaction');

    Route::post('transactions', [TransactionController::class, 'store']);

    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])
        ->middleware('can:view,transaction');
    Route::put('transactions/{transaction}', [CategoryController::class, 'update'])
        ->middleware('can:update,transaction');
});

Route::post('vcards', [VcardController::class, 'store']);
