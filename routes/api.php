<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\TestReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('permissions', PermissionController::class);

    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

    // emails
    Route::get('/test-email-blade', [TestMailController::class, 'blade']);
    Route::get('/test-email-markdown', [TestMailController::class, 'markdown']);
    Route::get('/test-email-simple', [TestMailController::class, 'simple']);
    Route::get('/test-email-with-attachment', [TestMailController::class, 'withAttachment']);
    Route::get('/test-email-with-pdf', [TestMailController::class, 'withPdf']);

    // pdf
    Route::get('/test-pdf', [TestReportController::class, 'generatePdf']);
});


