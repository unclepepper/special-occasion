<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Enum;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case NEW    = 'new';

    case BLOCKED = 'blocked';

    public const VALUES = [
        'new',
        'active',
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
