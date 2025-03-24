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
    public ?UserProfileEventUid $event { get; }

    /** Id  профиля  */
    public ?UserProfileUid $profile { get; }

    /** username, login, nik  */
    public ?string $username { get; }

    /** пол юзера */
    public ?UserGenderEnum $gender  { get; }

    /** Дата рождения  */
    public ?DateTimeImmutable $birthday { get; }
}
