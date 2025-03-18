<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth', 'check-ban'])
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/users', [UserController::class, 'index'])->can('viewAny', User::class);
        Route::post('/users', [UserController::class, 'create'])->can('viewAny', User::class);

        Route::prefix('/users/{user}')
            ->where(['user', '[0-9]+'])
            ->group(function () {
                Route::get('/', [UserController::class, 'show']);
                Route::patch('/', [UserController::class, 'update']);
                Route::put('/ban', [UserController::class, 'ban']);
                Route::put('/unban', [UserController::class, 'unban']);
                Route::put('/change-password', [UserController::class, 'updatePassword']);
            });
    });
