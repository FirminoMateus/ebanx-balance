<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ResetController;

Route::post('/reset', ResetController::class);
Route::get('/balance', BalanceController::class);
Route::post('/event', EventController::class);