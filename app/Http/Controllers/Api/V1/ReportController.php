<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\DailyAttendanceExport;
use App\Models\Attendance;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Attributes as OA;

class ReportController extends Controller
{
    #[OA\Get(path: '/api/v1/reports/attendance/daily', summary: 'Daily attendance report (PDF or Excel)', tags: ['Reports'])]
    #[OA\Parameter(name: 'date', in: 'query', required: false, schema: new OA\Schema(type: 'string', format: 'date'))]
    #[OA\Parameter(name: 'format', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['pdf', 'xlsx']))]
    #[OA\Response(response: 200, description: 'Report file stream')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    public function dailyAttendance(Request $request)
    {
        $date = $request->query('date');
        $date = $date ? Carbon::parse($date) : today();
        $format = $request->query('format', 'pdf');

        if ($format === 'xlsx') {
            return Excel::download(new DailyAttendanceExport($date), 'daily-attendance-'.$date->toDateString().'.xlsx');
        }

        $attendances = Attendance::query()
            ->with('employee')
            ->whereDate('checked_in_at', $date->toDateString())
            ->orderBy('checked_in_at')
            ->get();

        $pdf = SnappyPdf::loadView('reports.daily-attendance', [
            'date' => $date,
            'attendances' => $attendances,
        ]);

        return $pdf->download('daily-attendance-'.$date->toDateString().'.pdf');
    }
}

