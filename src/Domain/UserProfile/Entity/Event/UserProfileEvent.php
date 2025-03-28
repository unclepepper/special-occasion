<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Entity\Event;

use App\Domain\Common\Aggregate\AbstractEntity;
use App\Domain\UserProfile\Dto\UserProfileDto;
use App\Domain\UserProfile\Entity\Info\UserProfileInfo;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Type\Event\UserProfileEventUid;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users_profile_event')]
class UserProfileEvent extends AbstractEntity
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

    #[Assert\Valid]
    #[ORM\OrderBy(['date' => 'ASC'])]
    #[ORM\OneToOne(targetEntity: UserProfileInfo::class, inversedBy: 'event', cascade: ['all'])]
    private ?UserProfileInfo $info = null;

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

    public function setRoot(false|UserProfile|UserProfileUid $profile): void
    {
        if (false !== $profile) {
            $this->profile = $profile instanceof UserProfile ? $profile->getId() : $profile;
        }
    }

    public function getRoot(): ?UserProfileUid
    {
        return $this->profile;
    }

    public function getEntity(UserProfileDto $dto): UserProfileDto
    {
        $dto->username = $this->username;
        $dto->info     = $this->info;
        $dto->gender   = $this->gender;
        $dto->birthday = $this->birthday;
        $dto->profile  = $this->profile;

        return $dto;
    }

    /**
     * @param UserProfileDto $dto
     *
     * @return $this
     *
     * @throws ReflectionException
     */
    public function setEntity($dto): self
    {
        parent::setEntity($dto);

        $this->username = $dto->username ?: '';

        $this->gender   = $dto->gender ?: UserGenderEnum::UNKNOWN;

        if (false !== $dto->birthday) {
            $this->birthday = $dto->birthday;
        }

        if (false !== $dto->info) {
            $this->info = $dto->info;
        }

        return $this;
    }
}
