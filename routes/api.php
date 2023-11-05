<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcardController;

Route::patch('vcards/{vcard}/blocked', [VcardController::class, 'updateBlocked']);
Route::apiResource('vcards', VcardController::class);
