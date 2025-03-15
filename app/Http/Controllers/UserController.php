<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $users = User::paginate($perPage);

        $response = [
            'users' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'perPage' => $users->perPage(),
                'currentPage' => $users->currentPage()
            ]
        ];

        return $this->getSuccessfulResponse($response);
    }

    public function create(Request $request): JsonResponse
    {
        $roleTable = config('permission.table_names.roles');

        $validated = $request->validate([
            'name' => 'required|string',
            'last_name' => 'string',
            'login' => 'string|unique:users',
            'password' => 'required|string',
            'scopes.*' => "distinct|exists:$roleTable,name"
        ]);

        $user = User::create($validated);

        if (isset($validated['scopes'])) {
            $user->assignRole($validated['scopes']);
        }

        return $this->getSuccessfulResponse(message: 'User successfully created');
    }
}
