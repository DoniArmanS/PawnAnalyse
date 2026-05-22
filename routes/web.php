<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProgressionController;
use App\Http\Controllers\GameAnalysisController;
use App\Http\Controllers\DeepIntelligenceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connect', [AuthController::class, 'connect'])->name('connect');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\CheckChessSession::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/progression', [ProgressionController::class, 'index'])->name('progression');
    Route::get('/analysis', [GameAnalysisController::class, 'index'])->name('analysis');
    Route::get('/intelligence', [DeepIntelligenceController::class, 'index'])->name('intelligence');
});
