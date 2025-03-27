<?php

namespace App\Domain\UserProfile\Type\Info;

use App\Domain\Common\UuidType\UuidType;

class UserProfileInfoUidType extends UuidType
{
    public function getClassType(): string
    {
        return UserProfileInfoUid::class;
    }

    public function getName(): string
    {
        return UserProfileInfoUid::TYPE;
    }
}
