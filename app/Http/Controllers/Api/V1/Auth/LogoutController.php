<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/v1/auth/logout', summary: 'Logout', tags: ['Auth'])]
#[OA\Response(response: 200, description: 'Logout successful')]
#[OA\Response(response: 401, description: 'Unauthenticated')]
class LogoutController extends Controller
{
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
}

