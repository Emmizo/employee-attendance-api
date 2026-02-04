<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/v1/auth/login', summary: 'Login', tags: ['Auth'])]
#[OA\RequestBody(required: true, content: new OA\JsonContent(required: ['email', 'password'], properties: [
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
]))]
#[OA\Response(response: 200, description: 'Login successful')]
#[OA\Response(response: 401, description: 'Invalid credentials')]
class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = Auth::guard('web')->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->success([
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
