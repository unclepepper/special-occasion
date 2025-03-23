<?php

namespace App\Tests\Domain\Users;

use App\Domain\Common\UuidType\Uid;
use App\Domain\Users\Entity\User;
use App\Domain\Users\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class NewUserTest extends KernelTestCase
{
    public const string TEST_ID = '7aade041-6858-42c2-8fc4-81677fe7b99c';

    public function testNewUser(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $newUser = new User(self::TEST_ID);
        $em->persist($newUser);
        $em->flush();

        $userRepository = self::getContainer()->get(UserRepositoryInterface::class);

        $user = $userRepository->findUserById(self::TEST_ID);

        self::assertEquals($newUser->getId(), $user->getId());
        self::assertInstanceOf(Uid::class, $user->getId());
        self::assertInstanceOf(User::class, $user);
    }
}
