<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Type\UserProfile;

use App\Domain\Common\UuidType\UuidType;

class UserProfileUidType extends UuidType
{
    public function getClassType(): string
    {
        return UserProfileUid::class;
    }

    public function getName(): string
    {
        return UserProfileUid::TYPE;
    }
}
