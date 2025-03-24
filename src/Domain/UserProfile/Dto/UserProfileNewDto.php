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

class UserProfileNewDto implements UserProfileEventInterface
{
    public ?UserProfileEventUid $event {
        set(UserProfileEventUid|UserProfileEvent|string|null $value) {
            if (is_string($value)) {
                $value = new UserProfileEventUid(trim($value));
            }
            if ($value instanceof UserProfileEvent) {
                $value = $value->getId();
            }
            $this->event = $value;
        }
        get {
            return $this->event;
        }
    }
    public ?UserProfileUid $profile {
        set(UserProfileUid|UserProfile|null $value) {
            if ($value instanceof UserProfile) {
                $value = $value->getId();
            }
            $this->profile = $value;
        }
        get {
            return $this->profile;
        }
    }
    public ?string $username {
        set {
            $this->username = $value;
        }
        get {
            return $this->username;
        }
    }
    public ?UserGenderEnum $gender {
        set{
            $this->gender = $value;
        }
        get {
            return $this->gender;
        }
    }
    public ?DateTimeImmutable $birthday {
        set {
            $this->birthday = $value;
        }
        get {
            return $this->birthday;
        }
    }
}
