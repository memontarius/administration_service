<?php

namespace App\Models\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ANALYTIC = 'analytic';
    case NOTIFICATION_MANAGER = 'notification_manager';
    case FEED_MANAGER = 'feed_manager';
    case TICKET_MANAGER = 'ticket_manager';
    case SUPPORT = 'support';

    public static function values(): array
    {
         return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
