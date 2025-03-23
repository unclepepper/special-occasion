<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\UserRepository;

use App\Domain\Users\Entity\User;
use App\Domain\Users\Repository\GetUserByIdRepositoryInterface;
use App\Domain\Users\Type\UserUid;
use Doctrine\ORM\EntityManagerInterface;

class GetUserByIdRepository implements GetUserByIdRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getById(UserUid $userUid): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb
            ->select('users')
            ->from(User::class, 'users')
            ->where('users.id = :userUid')
            ->setParameter('userUid', $userUid, UserUid::TYPE)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
