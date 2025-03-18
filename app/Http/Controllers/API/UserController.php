<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateBanRequest;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\DetailedResource;
use App\Http\Resources\User\IndexResource;
use App\Models\Ban;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        return ResponseService::success(new IndexResource($users));
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
        Gate::authorize('view', $user);

        $userResource = new DetailedResource($user);

        return ResponseService::success($userResource);
    }

    public function update(UpdateRequest $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        Gate::authorize('update', $user);

        $validatedData = $request->validated();
        $user->update($validatedData);

        if (isset($validatedData['scopes'])) {
            $user->syncRoles($validatedData['scopes']);
        }

        return ResponseService::success(message: 'User successfully updated');
    }

    public function updatePassword(UpdatePasswordRequest $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        Gate::authorize('updatePassword', $user);

        $user->update($request->validated());

        return ResponseService::success(message: 'User password successfully updated');
    }

    public function ban(CreateBanRequest $request, int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        Gate::authorize('ban', $user);

        if (!$user->bans->isEmpty()) {
            return ResponseService::failed(message: 'User already banned', statusCode: 400);
        }

        $user->tokens()->delete();

        Ban::create([
            'user_id' => $userId,
            ...$request->validated()
        ]);

        return ResponseService::success(message: 'User successfully banned');
    }

    public function unban(int $userId): JsonResponse
    {
        $user = $this->getUserOrFail($userId);
        Gate::authorize('ban', $user);

        $bans = $user->bans();

        if (!$bans->exists()) {
            return ResponseService::failed(message: 'User has no bans', statusCode: 404);
        }

        $bans->delete();

        return ResponseService::success(message: 'User successfully unbanned');
    }
}
