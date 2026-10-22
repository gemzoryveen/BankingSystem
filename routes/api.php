<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
