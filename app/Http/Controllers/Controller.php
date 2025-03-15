<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function getSuccessfulResponse(array $payload = null, string $message = ''): JsonResponse
    {
        $responseData = [
            'success' => true,
        ];

        if (!empty($message)) {
            $responseData['message'] = $message;
        }

        if (!empty($payload)) {
            $responseData['data'] = $payload;
        }

        return response()->json($responseData);
    }

    protected function getFailedResponse(string $message, array $errors = null, int $statusCode = 200): JsonResponse
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
}
