<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Entity\Event;

use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Type\Event\UserProfileEventUid;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use DateTimeImmutable;

interface UserProfileEventInterface
{
    /** Id события профиля  */
    public false|string|UserProfileEventUid $event { get; }
//
//    /** Id  профиля  */
//    public false|UserProfileUid $profile { get; }
//
//    /** username, login, nik  */
//    public false|string $username { get; }
//
//    /** пол юзера */
//    public false|UserGenderEnum $gender  { get; }
//
//    /** Дата рождения  */
//    public DateTimeImmutable|false $birthday { get; }
}
