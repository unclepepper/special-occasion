<?php

declare(strict_types = 1);

namespace App\Domain\Users\Entity;

use App\Domain\Users\Type\UserUid;
use App\Infrastructure\Doctrine\Repository\UserRepository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'usr', type: UserUid::TYPE)]
    private UserUid $id;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    public function __construct(string $id = null) {
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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(?array $role): void
    {
        if($role)
        {
            $this->roles = $role;
        }
    }

    public function getUserIdentifier(): string
    {
        return  (string) $this->id;
    }

    public function eraseCredentials(): void {}
}
