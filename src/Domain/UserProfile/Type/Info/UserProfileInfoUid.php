<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Type\Info;

use App\Domain\Common\UuidType\Uid;

class UserProfileInfoUid extends Uid
{
    public const TYPE = 'user_profile_info';
}
