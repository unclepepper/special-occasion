<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Entity\Info;

use App\Domain\Common\Aggregate\AbstractEntity;
use App\Domain\UserProfile\Dto\UserProfileInfoDto;
use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Enum\UserStatusEnum;
use App\Domain\UserProfile\Type\Info\UserProfileInfoUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'users_profile_info')]
class UserProfileInfo extends AbstractEntity
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: UserProfileInfoUid::TYPE)]
    private UserProfileInfoUid $id;

    /**
     * ID профиля пользователя.
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\OneToOne(targetEntity: UserProfileEvent::class, inversedBy: 'info')]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    private UserProfileEvent $event;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $surname;

    #[ORM\Column(type: 'string', length: 255, nullable: true, enumType: UserStatusEnum::class)]
    private ?UserStatusEnum $status = UserStatusEnum::INACTIVE;

    public function __construct(UserProfileEvent $event)
    {
        $this->id    =  new UserProfileInfoUid();
        $this->event = $event;
    }

    public function __toString(): string
    {
        return (string) $this->event;
    }

    public function getId(): UserProfileInfoUid
    {
        return $this->id;
    }

    public function getEntity(UserProfileInfoDto $dto): UserProfileInfoDto
    {
        $dto->name    = $this->name;
        $dto->surname = $this->surname;
        $dto->status  = $this->status;

        return $dto;
    }

    /**
     * @param UserProfileInfoDto $dto
     *
     * @throws ReflectionException
     */
    public function setEntity($dto): self
    {
        if (!$dto->name || !$dto->surname) {
            throw new InvalidArgumentException('Invalid dto');
        }
        parent::setEntity($dto);

        $this->name    = $dto->name;
        $this->surname = $dto->surname;
        $this->status  = $dto->status ?: null;

        return $this;
    }
}
