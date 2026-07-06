<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\TestReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('test-mail')->group(function () {
    Route::get('/blade', [TestMailController::class, 'blade']);
    Route::get('/markdown', [TestMailController::class, 'markdown']);
    Route::get('/simple', [TestMailController::class, 'simple']);
    Route::get('/with-attachment', [TestMailController::class, 'withAttachment']);
    Route::get('/with-pdf', [TestMailController::class, 'withPdf']);
});

Route::prefix('test-pdf')->group(function () {
    Route::get('/generate', [TestReportController::class, 'generatePdf']);
});

// Rutas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren estar logueado)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        'user' => UserController::class
    ]);
});
