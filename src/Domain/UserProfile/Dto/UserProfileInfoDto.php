<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Dto;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Info\UserProfileInfoInterface;
use App\Domain\UserProfile\Enum\UserStatusEnum;
use App\Domain\UserProfile\Type\Info\UserProfileInfoUid;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

class UserProfileInfoDto implements UserProfileInfoInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public false|UserProfileInfoUid $id = false{
        set(UserProfileInfoUid|null|false $value)  {
            $this->id = $value ?? false;
        }
        get {
            return $this->id;
        }
    }

    #[Assert\NotBlank]
    public false|string $name = false {
        set(string|null|false $value)  {
            $this->name = $value ?? false;
        }
        get{
            return $this->name;
        }
    }

    #[Assert\NotBlank]
    public false|string $surname = false{
        set(string|null|false $value)  {
            $this->surname = $value ?? false;
        }
        get{
            if (!$this->surname) {
                throw new RuntimeException('Surname not set');
            }

            return $this->surname;
        }
    }

    #[Assert\NotBlank]
    public false|UserStatusEnum $status = false {
        set(UserStatusEnum|null|false $value)  {
            $this->status = $value ?? false;
        }
        get{
            return $this->status;
        }
    }

    public false|UserProfileEvent $event = false{
        set(false|null|UserProfileEvent $value)  {
            $this->event = $value ?? false;
        }
        get {
            return $this->event;
        }
    }
}
