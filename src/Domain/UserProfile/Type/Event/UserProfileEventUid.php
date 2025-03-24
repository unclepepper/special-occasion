<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Type\Event;

use App\Domain\Common\UuidType\Uid;

class UserProfileEventUid extends Uid
{
    public const TYPE = 'user_profile_event';
}
