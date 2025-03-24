<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Type\UserProfile;

use App\Domain\Common\UuidType\Uid;

class UserProfileUid extends Uid
{
    public const TYPE = 'user_profile';
}
