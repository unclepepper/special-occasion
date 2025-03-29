<?php

namespace App\Domain\UserProfile\Entity\Info;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;

interface UserProfileInfoInterface
{
    public false|UserProfileEvent $event { get; }
}
