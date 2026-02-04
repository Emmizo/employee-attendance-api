<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/v1/auth/reset-password', summary: 'Reset password', tags: ['Auth'])]
#[OA\Response(response: 200, description: 'Password reset successful')]
#[OA\Response(response: 422, description: 'Invalid token / validation error')]
class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $status = Password::broker()->reset(
            $payload,
            function ($user) use ($payload): void {
                $user->forceFill([
                    'password' => Hash::make($payload['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                // Stateless auth: reset should invalidate existing tokens.
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error('Unable to reset password', 422, [
                'email' => [__($status)],
            ]);
        }

        return $this->success([
            'message' => 'Password reset successful.',
        ]);
    }
}

