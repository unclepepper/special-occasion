<?php

namespace App\Domain\UserProfile\Type\Event;

use App\Domain\Common\UuidType\UuidType;

class UserProfileEventUidType extends UuidType
{
    public function getClassType(): string
    {
        return UserProfileEventUid::class;
    }

    public function getName(): string
    {
        return UserProfileEventUid::TYPE;
    }
}
