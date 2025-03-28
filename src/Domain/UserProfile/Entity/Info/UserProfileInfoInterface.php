<?php

namespace App\Domain\UserProfile\Entity\Info;

use App\Domain\UserProfile\Type\Event\UserProfileEventUid;

interface UserProfileInfoInterface
{
    public false|string|UserProfileEventUid $event { get; }
}
