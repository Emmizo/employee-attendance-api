<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Services\AuthService;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/v1/auth/register', summary: 'Register a new user', tags: ['Auth'])]
#[OA\RequestBody(required: true, content: new OA\JsonContent(required: ['name', 'email', 'password', 'password_confirmation'], properties: [
    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password'),
]))]
#[OA\Response(response: 201, description: 'User registered successfully')]
#[OA\Response(response: 422, description: 'Validation error')]
class RegisterController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->created([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
