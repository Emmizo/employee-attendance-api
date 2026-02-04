<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    $auth = App\Http\Controllers\Api\V1\Auth\AuthController::class;

    // Public auth routes
    Route::post('/auth/register', [$auth, 'register']);
    Route::post('/auth/login', [$auth, 'login']);
    Route::post('/auth/forgot-password', [$auth, 'sendResetLink']);
    Route::post('/auth/reset-password', [$auth, 'reset']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () use ($auth) {
        Route::post('/auth/logout', [$auth, 'logout']);

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
