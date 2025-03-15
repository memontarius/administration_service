<?php

namespace App\Models\Enums;

enum UserPermission: string
{
    case USER_MANAGEMENT = 'user_management';
    case ANALYTICS_MANAGEMENT = 'analytics_management';
}
