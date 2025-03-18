<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        /** @var User $user */
        $user = User::where('login', $credentials['login'])->get()->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return ResponseService::failed('Credentials are invalid.', statusCode: 401);
        }

        $user->tokens()->delete();

        if ($user->isBanned()) {
            ResponseService::abortAsBanned();
        }

        return ResponseService::success([
            'token' => $user->createToken('token')->accessToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            ResponseService::abortAsUnauthenticated();
        }

        $user->tokens()->delete();

        return ResponseService::success(message: 'Successfully logged out.');
    }
}
