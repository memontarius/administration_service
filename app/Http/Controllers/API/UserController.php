<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\DetailedResource;
use App\Http\Resources\User\PaginatedResource;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class  UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');

        if ($search) {
            $names = explode(" ", $search);

            $users = User::where(function ($query) use ($names) {
                $query->whereIn('name', $names);
                $query->orWhere(function ($query) use ($names) {
                    $query->whereIn('last_name', $names);
                });
            })->with('roles')->paginate($perPage);
        } else {
            $users = User::with('roles')->paginate($perPage);
        }

        return ResponseService::success(new PaginatedResource($users));
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = User::create($validatedData);

        if (isset($validatedData['scopes'])) {
            $user->assignRole($validatedData['scopes']);
        }

        return ResponseService::success(message: 'User successfully created');
    }

    public function show(Request $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        $userResource = new DetailedResource($user);

        return ResponseService::success($userResource);
    }

    public function update(UpdateRequest $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        $user->update($request->validated());

        if (isset($validated['scopes'])) {
            $user->syncRoles($validated['scopes']);
        }

        return ResponseService::success(message: 'User successfully updated');
    }

    public function updatePassword(UpdatePasswordRequest $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        $user->update($request->validated());

        return ResponseService::success(message: 'User password successfully updated');
    }
}
