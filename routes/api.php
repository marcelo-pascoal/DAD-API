<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::patch('vcards/{vcard}/blocked', [VcardController::class, 'updateBlocked']);
Route::apiResource('vcards', VcardController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('categories', CategoryController::class);
