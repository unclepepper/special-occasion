<?php

namespace App\Domain\User\Type;

use App\Domain\Common\UuidType\UuidType;

class UserUidType extends UuidType
{
    public function getClassType(): string
    {
        return UserUid::class;
    }

    public function getName(): string
    {
        return UserUid::TYPE;
    }
}
