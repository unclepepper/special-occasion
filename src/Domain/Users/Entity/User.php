<?php

declare(strict_types=1);

namespace App\Domain\Users\Entity;

use App\Domain\Users\Type\UserUid;
use App\Infrastructure\Doctrine\Repository\UserRepository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: UserUid::TYPE)]
    private UserUid $id;

    /**
     * @var array<mixed>|string[]
     */
    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    public function __construct(?string $id = null)
    {
        $this->id = new UserUid($id);
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): UserUid
    {
        return $this->id;
    }

    /**
     * @return array<mixed>
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param null|array<mixed> $role
     */
    public function setRoles(?array $role): void
    {
        if ($role) {
            $this->roles = $role;
        }
    }

    /**
     * @return non-empty-string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function eraseCredentials(): void {}
}
