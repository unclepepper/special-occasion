<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Entity;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Type\Event\UserProfileEventUid;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'user_profile')]
class UserProfile
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: UserProfileUid::TYPE)]
    private UserProfileUid $id;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: UserProfileEventUid::TYPE)]
    private UserProfileEventUid $event;

    public function __construct(?string $id = null)
    {
        $this->id = new UserProfileUid($id);
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): UserProfileUid
    {
        return $this->id;
    }

    public function getEvent(): UserProfileEventUid
    {
        return $this->event;
    }

    public function setEvent(UserProfileEvent|UserProfileEventUid $event): void
    {
        $this->event = $event instanceof UserProfileEvent ? $event->getId() : $event;
    }
}
