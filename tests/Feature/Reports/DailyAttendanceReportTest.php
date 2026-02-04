<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\Sanctum;
use Maatwebsite\Excel\Facades\Excel;

it('requires authentication for daily attendance report', function () {
    $this->getJson('/api/v1/reports/attendance/daily')->assertUnauthorized();
});

it('returns a pdf daily attendance report', function () {
    Sanctum::actingAs(User::factory()->create());

    SnappyPdf::shouldReceive('loadView')
        ->once()
        ->andReturnUsing(function () {
            $mock = Mockery::mock();
            $mock->shouldReceive('download')->once()->andReturn(Response::make('PDF', 200, [
                'Content-Type' => 'application/pdf',
            ]));

            return $mock;
        });

    $employee = Employee::factory()->create();
    Attendance::factory()->create(['employee_id' => $employee->id]);

    $response = $this->get('/api/v1/reports/attendance/daily?date='.Carbon::today()->toDateString().'&format=pdf');

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf');
});

it('returns an excel daily attendance report', function () {
    Sanctum::actingAs(User::factory()->create());

    Excel::fake();

    $employee = Employee::factory()->create();
    Attendance::factory()->create(['employee_id' => $employee->id]);

    $response = $this->get('/api/v1/reports/attendance/daily?date='.Carbon::today()->toDateString().'&format=xlsx');

    $response->assertOk();
    Excel::assertDownloaded('daily-attendance-'.Carbon::today()->toDateString().'.xlsx');
});

