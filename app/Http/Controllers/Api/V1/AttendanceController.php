<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Attendance\CheckInRequest;
use App\Http\Requests\Api\V1\Attendance\CheckOutRequest;
use App\Services\AttendanceService;
use App\Services\Exceptions\AttendanceConflictException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Attendance')]
class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    #[OA\Post(path: '/api/v1/attendance/check-in', summary: 'Check in an employee', tags: ['Attendance'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'employee_id', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'employee_identifier', type: 'string', nullable: true, example: 'EMP-001'),
        new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Arrived on time'),
    ]))]
    #[OA\Response(response: 201, description: 'Checked in')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 409, description: 'Conflict')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function checkIn(CheckInRequest $request): JsonResponse
    {
        try {
            $attendance = $this->attendanceService->checkIn($request->employee(), $request->validated('notes'));
        } catch (AttendanceConflictException $e) {
            return $this->error($e->getMessage(), 409);
        }

        return $this->created([
            'attendance' => [
                'id' => $attendance->id,
                'employee_id' => $attendance->employee_id,
                'checked_in_at' => $attendance->checked_in_at,
                'checked_out_at' => $attendance->checked_out_at,
                'notes' => $attendance->notes,
            ],
        ]);
    }

    #[OA\Post(path: '/api/v1/attendance/check-out', summary: 'Check out an employee', tags: ['Attendance'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: 'employee_id', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'employee_identifier', type: 'string', nullable: true, example: 'EMP-001'),
        new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Left early for appointment'),
    ]))]
    #[OA\Response(response: 200, description: 'Checked out')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 409, description: 'Conflict')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function checkOut(CheckOutRequest $request): JsonResponse
    {
        try {
            $attendance = $this->attendanceService->checkOut($request->employee(), $request->validated('notes'));
        } catch (AttendanceConflictException $e) {
            return $this->error($e->getMessage(), 409);
        }

        return $this->success([
            'attendance' => [
                'id' => $attendance->id,
                'employee_id' => $attendance->employee_id,
                'checked_in_at' => $attendance->checked_in_at,
                'checked_out_at' => $attendance->checked_out_at,
                'notes' => $attendance->notes,
            ],
        ]);
    }
}

