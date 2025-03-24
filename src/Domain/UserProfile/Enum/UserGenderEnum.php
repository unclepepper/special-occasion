<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Enum;

enum UserGenderEnum: string
{
    case MALE = 'male';

    case FEMALE = 'female';

    case UNKNOWN        = 'unknown';
    public const VALUES = [
        'male',
        'female',
        'unknown',
    ];

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
