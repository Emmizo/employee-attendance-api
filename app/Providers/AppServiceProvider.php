<?php

namespace App\Providers;

use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Eloquent\AttendanceRepository;
use App\Repositories\Eloquent\EmployeeRepository;
use App\Services\AttendanceNotifier;
use App\Services\Contracts\AttendanceNotifierInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(AttendanceNotifierInterface::class, AttendanceNotifier::class);
    }

    public function boot(): void
    {
        //
    }
}
