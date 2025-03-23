<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\UserRepository;

use App\Domain\Users\Entity\User;
use App\Domain\Users\Repository\GetUserByIdRepositoryInterface;
use App\Domain\Users\Repository\UserRepositoryInterface;
use App\Domain\Users\Type\UserUid;
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
