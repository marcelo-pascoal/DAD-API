<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VcardController;

Route::apiResource('vcards', VcardController::class);
