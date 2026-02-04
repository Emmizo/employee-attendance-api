<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Employees\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\Employees\UpdateEmployeeRequest;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Employees')]
class EmployeeController extends Controller
{
    public function __construct(private readonly EmployeeService $employeeService)
    {
    }

    #[OA\Get(path: '/v1/employees', summary: 'List employees', tags: ['Employees'])]
    #[OA\Response(response: 200, description: 'Employees list')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Forbidden')]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->employeeService->paginate((int) $request->query('per_page', 15));

        return $this->success(EmployeeResource::collection($paginator)->response()->getData(true));
    }

    #[OA\Post(path: '/v1/employees', summary: 'Create employee', tags: ['Employees'])]
    #[OA\Response(response: 201, description: 'Employee created')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Forbidden')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->create($request->validated());

        return $this->created(new EmployeeResource($employee));
    }

    #[OA\Get(path: '/v1/employees/{id}', summary: 'Show employee', tags: ['Employees'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Employee')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Forbidden')]
    #[OA\Response(response: 404, description: 'Not found')]
    public function show(Employee $employee): JsonResponse
    {
        return $this->success(new EmployeeResource($employee));
    }

    #[OA\Put(path: '/v1/employees/{id}', summary: 'Update employee', tags: ['Employees'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Employee updated')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Forbidden')]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee = $this->employeeService->update($employee, $request->validated());

        return $this->success(new EmployeeResource($employee));
    }

    #[OA\Delete(path: '/v1/employees/{id}', summary: 'Delete employee', tags: ['Employees'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Employee deleted')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Forbidden')]
    #[OA\Response(response: 404, description: 'Not found')]
    public function destroy(Employee $employee): JsonResponse
    {
        $this->employeeService->delete($employee);

        return $this->success([
            'message' => 'Employee deleted',
        ]);
    }
}

