<?php

namespace App\Repositories\Contracts;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Employee;

    public function findByEmail(string $email): ?Employee;

    public function findByEmployeeIdentifier(string $identifier): ?Employee;

    public function create(array $data): Employee;

    public function update(Employee $employee, array $data): Employee;

    public function delete(Employee $employee): bool;
}
