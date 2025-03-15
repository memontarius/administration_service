<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request): JsonResponse
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
}
