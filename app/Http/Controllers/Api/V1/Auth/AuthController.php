<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Mail\PasswordResetConfirmationMail;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    #[OA\Post(path: '/api/v1/auth/register', summary: 'Register a new user', tags: ['Auth'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(required: ['name', 'email', 'password', 'password_confirmation'], properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password'),
    ]))]
    #[OA\Response(response: 201, description: 'User registered successfully')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function register(RegisterRequest $request): JsonResponse
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

    #[OA\Post(path: '/api/v1/auth/login', summary: 'Login', tags: ['Auth'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(required: ['email', 'password'], properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
    ]))]
    #[OA\Response(response: 200, description: 'Login successful')]
    #[OA\Response(response: 401, description: 'Invalid credentials')]
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

    #[OA\Post(path: '/api/v1/auth/logout', summary: 'Logout', tags: ['Auth'])]
    #[OA\Response(response: 200, description: 'Logout successful')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user === null) {
            return $this->error('Unauthenticated', 401);
        }

        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return $this->success([
            'message' => 'Logged out',
        ]);
    }

    #[OA\Post(path: '/api/v1/auth/forgot-password', summary: 'Send password reset link', tags: ['Auth'])]
    #[OA\Response(response: 200, description: 'Reset link sent (if email exists)')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse
    {
        Password::broker()->sendResetLink($request->validated());

        return $this->success([
            'message' => 'If the email exists, a reset link has been sent.',
        ]);
    }

    #[OA\Post(path: '/api/v1/auth/reset-password', summary: 'Reset password', tags: ['Auth'])]
    #[OA\Response(response: 200, description: 'Password reset successful')]
    #[OA\Response(response: 422, description: 'Invalid token / validation error')]
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $user = null;

        $status = Password::broker()->reset(
            $payload,
            function ($resetUser) use ($payload, &$user): void {
                $resetUser->forceFill([
                    'password' => Hash::make($payload['password']),
                    'remember_token' => Str::random(60),
                ])->save();
                $resetUser->tokens()->delete();
                $user = $resetUser;
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error('Unable to reset password', 422, [
                'email' => [__($status)],
            ]);
        }

        // Send confirmation email
        if ($user !== null) {
            Mail::to($user->email)->send(new PasswordResetConfirmationMail($user));
        }

        return $this->success([
            'message' => 'Password reset successful.',
        ]);
    }
}
