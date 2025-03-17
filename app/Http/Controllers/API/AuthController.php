<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->getFailedResponse(
                'Validation input error.',
                $validator->errors()->toArray(), 400);
        }

        $credentials = $validator->validated();
        $user = User::where('login', $credentials['login'])->get()->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return $this->getFailedResponse('Credentials are invalid.');
        }

        $responseData = [
            'token' => $user->createToken('token')->accessToken
        ];

        return $this->getSuccessfulResponse(payload: $responseData);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->getFailedResponse('Unauthenticated.', statusCode: 401);
        }

        $user->tokens->each(function($token, $key) {
            $token->delete();
        });

        return $this->getSuccessfulResponse(message: 'Successfully logged out.');
    }
}
