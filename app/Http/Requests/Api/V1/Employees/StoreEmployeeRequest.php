<?php

namespace App\Http\Requests\Api\V1\Employees;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email'],
            'employee_identifier' => ['required', 'string', 'max:100', 'unique:employees,employee_identifier'],
            'phone_number' => ['nullable', 'string', 'max:50'],
        ];
    }
}

