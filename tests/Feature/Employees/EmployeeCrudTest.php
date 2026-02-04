<?php

use App\Models\Employee;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('requires authentication for employee endpoints', function () {
    $this->getJson('/api/v1/employees')->assertUnauthorized();
});

it('forbids non-admin users from accessing employee endpoints', function () {
    Sanctum::actingAs(User::factory()->create(['is_admin' => false]));

    $this->getJson('/api/v1/employees')->assertForbidden();
});

it('allows an admin to perform employee CRUD', function () {
    Sanctum::actingAs(User::factory()->admin()->create());

    $employee = Employee::factory()->create();

    $this->getJson('/api/v1/employees')
        ->assertOk()
        ->assertJsonPath('data.data.0.id', $employee->id);

    $createPayload = [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'employee_identifier' => 'EMP-99999',
        'phone_number' => '+250700000000',
    ];

    $this->postJson('/api/v1/employees', $createPayload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Alice');

    $createdId = (int) $this->postJson('/api/v1/employees', [
        'name' => 'Bob',
        'email' => 'bob@example.com',
        'employee_identifier' => 'EMP-88888',
        'phone_number' => null,
    ])->json('data.id');

    $this->getJson("/api/v1/employees/{$createdId}")
        ->assertOk()
        ->assertJsonPath('data.id', $createdId);

    $this->putJson("/api/v1/employees/{$createdId}", [
        'name' => 'Bob Updated',
    ])->assertOk()
        ->assertJsonPath('data.name', 'Bob Updated');

    $this->deleteJson("/api/v1/employees/{$createdId}")
        ->assertOk();

    expect(Employee::query()->whereKey($createdId)->exists())->toBeFalse();
});

