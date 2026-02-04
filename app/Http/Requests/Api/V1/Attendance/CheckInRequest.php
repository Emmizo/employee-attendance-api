<?php

namespace App\Http\Requests\Api\V1\Attendance;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required_without:employee_identifier', 'integer', 'exists:employees,id'],
            'employee_identifier' => ['required_without:employee_id', 'string', 'exists:employees,employee_identifier'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function employee(): Employee
    {
        $employeeId = $this->validated('employee_id');
        if ($employeeId) {
            return Employee::query()->findOrFail($employeeId);
        }

        return Employee::query()
            ->where('employee_identifier', $this->validated('employee_identifier'))
            ->firstOrFail();
    }
}

