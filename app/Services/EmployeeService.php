<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepositoryInterface $employees)
    {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));
        return $this->employees->paginate($perPage);
    }

    public function create(array $data): Employee
    {
        return $this->employees->create($data);
    }

    public function update(Employee $employee, array $data): Employee
    {
        return $this->employees->update($employee, $data);
    }

    public function delete(Employee $employee): void
    {
        $this->employees->delete($employee);
    }
}

