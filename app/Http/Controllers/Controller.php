<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseService;

abstract class Controller
{
    protected function getUserOrFail(int $userId)
    {
        $user = User::find($userId);
        if (empty($user)) {
            abort(ResponseService::failed("User not found", statusCode: 404));
        }
        return $user;
    }
}
