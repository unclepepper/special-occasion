<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\UserRepository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\GetUserByIdRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Type\UserUid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private GetUserByIdRepositoryInterface $userByIdRepository;

    public function __construct(ManagerRegistry $registry, GetUserByIdRepositoryInterface $getUserById)
    {
        parent::__construct($registry, User::class);
        $this->userByIdRepository = $getUserById;
    }

    public function findUserById(string|UserUid $id): ?User
    {
        if (is_string($id)) {
            $id = new UserUid($id);
        }

        return $this->userByIdRepository->getById($id);
    }
}
