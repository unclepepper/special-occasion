<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Dto;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Event\UserProfileEventInterface;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Type\Event\UserProfileEventUid;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use DateTimeImmutable;

class UserProfileDto implements UserProfileEventInterface
{
    public false|string|UserProfileEventUid $event = false {
        set(UserProfileEventUid|UserProfileEvent|string|null|false $value) {
            if (is_string($value)) {
                $value = new UserProfileEventUid(trim($value));
            }

            if ($value instanceof UserProfileEvent) {
                $value = $value->getId();
            }

            $this->event = $value ?? false;
        }
        get {
            return $this->event;
        }
    }

    public false|UserProfileUid $profile = false {
        set(UserProfileUid|UserProfile|null|false $value) {
            if ($value instanceof UserProfile) {
                $value = $value->getId();
            }
            $this->profile = $value ?? false;
        }
        get {
            return $this->profile;
        }
    }

    public false|string $username = false {
        set(string|false|null $value) {
            $this->username = $value ?? false;
        }
        get {
            return $this->username;
        }
    }

    public false|UserGenderEnum $gender = false {
        set(UserGenderEnum|false|null $value){
            $this->gender = $value ?? false;
        }
        get {
            return $this->gender;
        }
    }

    public DateTimeImmutable|false $birthday = false {
        set(DateTimeImmutable|false|null $value)  {
            $this->birthday = $value ?? false;
        }
        get {
            return $this->birthday;
        }
    }

    public false|UserProfileInfoDto $info = false {
        set(UserProfileInfoDto|null|false $value) {
            $this->info = $value ?? false;
        }
        get {
            return $this->info;
        }
    }
}
