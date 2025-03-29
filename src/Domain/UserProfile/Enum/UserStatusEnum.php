<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Enum;

enum UserStatusEnum: string
{
    case ACTIVE      = 'active';
    case INACTIVE    = 'inactive';

    case BLOCKED = 'blocked';

    public const VALUES = [
        'active',
        'inactive',
        'blocked',
    ];

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
