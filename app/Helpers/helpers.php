<?php

use Illuminate\Http\JsonResponse;

function errorJsonResponse(string $message, array  $errors = [], int $statusCode = 500): JsonResponse
{
    return response()->json([
        'status'  => 'error',
        'message' => $message,
        'errors'  => $errors
    ], $statusCode);
}

function successJsonResponse(string $message, array $data = [], int $statusCode = 200): JsonResponse
{
    return response()->json([
        'status'  => 'success',
        'message' => $message,
        'data'    => $data
    ], $statusCode);
}
