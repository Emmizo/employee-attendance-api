<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;

abstract class Controller extends \App\Http\Controllers\Controller
{
    protected function success(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ], $status);
    }

    protected function created(mixed $data = null): JsonResponse
    {
        return $this->success($data, 201);
    }

    protected function error(string $message, int $status = 400, mixed $errors = null): JsonResponse
    {
        $payload = ['message' => $message];
        if ($errors !== null) {
            $payload['errors'] = $errors;
        }
        return response()->json($payload, $status);
    }
}
