<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Entity\Event;

use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Type\Event\UserProfileEventUid;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users_profile_event')]
class UserProfileEvent
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: UserProfileEventUid::TYPE)]
    private UserProfileEventUid $id;

    /**
     * ID профиля пользователя.
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: UserProfileUid::TYPE)]
    private ?UserProfileUid $profile = null;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true, enumType: UserGenderEnum::class)]
    private UserGenderEnum $gender;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $birthday = null;

    public function __construct()
    {
        $this->id = new UserProfileEventUid();
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): UserProfileEventUid
    {
        return $this->id;
    }

    public function setRoot(UserProfile|UserProfileUid $profile): void
    {
        $this->profile = $profile instanceof UserProfile ? $profile->getId() : $profile;
    }

    public function getRoot(): ?UserProfileUid
    {
        return $this->profile;
    }

    public function setEntity(UserProfileEventInterface $dto): self
    {
        $this->username = $dto->username;
        $this->gender   = $dto->gender;
        $this->birthday = $dto->birthday;

        return $this;
        //        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
}
