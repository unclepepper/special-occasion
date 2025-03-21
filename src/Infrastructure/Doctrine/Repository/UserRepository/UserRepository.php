<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\UserRepository;

use App\Domain\Users\Entity\User;
use App\Domain\Users\Repository\GetUserByIdRepositoryInterface;
use App\Domain\Users\Repository\UserRepositoryInterface;
use App\Domain\Users\Type\UserUid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    private GetUserByIdRepositoryInterface $userByIdRepository;

    public function __construct(ManagerRegistry $registry, GetUserByIdRepositoryInterface $getUserById)
    {
        parent::__construct($registry, User::class);
        $this->userByIdRepository = $getUserById;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if(!$user instanceof User)
        {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        //        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }


    public function findUserById($id): ?object
    {

        $id = $id['id'] ?? $id;

        if(is_string($id))
        {
            $id = new UserUid($id);
        }

        return $this->userByIdRepository->getById($id);
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
