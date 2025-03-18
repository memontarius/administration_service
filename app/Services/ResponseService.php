<?php

namespace App\Services;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

final class ResponseService
{
    public static function success(JsonResource|array $resource = null, string $message = ''): JsonResponse
    {
        $responseData = [
            'success' => true,
        ];

        if (!empty($message)) {
            $responseData['message'] = $message;
        }

        if (!empty($resource)) {
            $responseData['data'] = $resource;
        }

        return response()->json($responseData);
    }

    public static function failed(string $message, array $errors = null, int $statusCode = 200): JsonResponse
    {
        $responseData = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $responseData['errors'] = $errors;
        }

        return response()->json($responseData, $statusCode);
    }

    public static function abortAsNotFound(): JsonResponse
    {
        abort(self::failed('Not found', statusCode: 404));
    }

    public static function abortAsInvalidRequest(array $errors = null): JsonResponse
    {
        abort(self::failed('Invalid request', $errors, 422));
    }

    public static function abortAsUnauthenticated(array $errors = null): JsonResponse
    {
        abort(self::failed('Unauthenticated', $errors, 401));
    }

    public static function abortAsUnauthorized(array $errors = null): JsonResponse
    {
        abort(self::failed('Unauthorized', $errors, 403));
    }

    public static function abortAsBanned(array $errors = null): JsonResponse
    {
        abort(self::failed('You are banned', $errors, 403));
    }
}
