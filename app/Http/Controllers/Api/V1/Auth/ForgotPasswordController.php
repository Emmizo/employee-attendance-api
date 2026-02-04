<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/v1/auth/forgot-password', summary: 'Send password reset link', tags: ['Auth'])]
#[OA\Response(response: 200, description: 'Reset link sent (if email exists)')]
#[OA\Response(response: 422, description: 'Validation error')]
class ForgotPasswordController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse
    {
        Password::broker()->sendResetLink($request->validated());

        // Avoid leaking whether the email exists.
        return $this->success([
            'message' => 'If the email exists, a reset link has been sent.',
        ]);
    }
}

