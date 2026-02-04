<?php

namespace App\Http\Requests\Api\V1\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'employee_identifier' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('employees', 'employee_identifier')->ignore($employeeId),
            ],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}

