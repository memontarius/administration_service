<?php

namespace App\Models\Enums;

enum Modules: string
{
    case ADMIN = 'admin';
    case ANALYTIC = 'analytic';
    case NOTIFICATIONS = 'notification';
    case FEED = 'feed';
    case TICKETS = 'tickets';
    case SUPPORT = 'support';

    public static function values(): array
    {
         return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
