<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/auth/register', [App\Http\Controllers\Api\V1\Auth\RegisterController::class, 'register']);
    Route::post('/auth/login', [App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login']);
    Route::post('/auth/forgot-password', [App\Http\Controllers\Api\V1\Auth\ForgotPasswordController::class, 'sendResetLink']);
    Route::post('/auth/reset-password', [App\Http\Controllers\Api\V1\Auth\ResetPasswordController::class, 'reset']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [App\Http\Controllers\Api\V1\Auth\LogoutController::class, 'logout']);

        Route::middleware('admin')->group(function () {
            Route::get('/employees', [App\Http\Controllers\Api\V1\EmployeeController::class, 'index']);
            Route::post('/employees', [App\Http\Controllers\Api\V1\EmployeeController::class, 'store']);
            Route::get('/employees/{employee}', [App\Http\Controllers\Api\V1\EmployeeController::class, 'show']);
            Route::put('/employees/{employee}', [App\Http\Controllers\Api\V1\EmployeeController::class, 'update']);
            Route::delete('/employees/{employee}', [App\Http\Controllers\Api\V1\EmployeeController::class, 'destroy']);
        });

        Route::post('/attendance/check-in', [App\Http\Controllers\Api\V1\AttendanceController::class, 'checkIn']);
        Route::post('/attendance/check-out', [App\Http\Controllers\Api\V1\AttendanceController::class, 'checkOut']);

        Route::get('/reports/attendance/daily', [App\Http\Controllers\Api\V1\ReportController::class, 'dailyAttendance']);
    });
});
