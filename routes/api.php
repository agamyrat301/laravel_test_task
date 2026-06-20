<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\MetricsController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'index']);
Route::get('/metrics', [MetricsController::class, 'index']);

Route::middleware('throttle:contact')->post('/contact', [ContactController::class, 'store']);
