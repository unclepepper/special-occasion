<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserProfile;

use App\Domain\UserProfile\Dto\UserProfileNewDto;
use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\UseCase\New\UserProfileHandler;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class NewUserProfileTest extends KernelTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testNewUserProfile(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        /** @var UserProfileHandler $handler */
        $handler = self::getContainer()->get(UserProfileHandler::class);

        /** Create Dto for UserProfile entity */
        $userProfileDto           = new UserProfileNewDto();
        $userProfileDto->username = 'TestUsername';
        $userProfileDto->birthday = new DateTimeImmutable('1981-03-07 08:30:00');
        $userProfileDto->gender   = UserGenderEnum::MALE;

        self::assertEquals($userProfileDto->username, 'TestUsername');
        self::assertEquals($userProfileDto->birthday->format('Y-m-d H:i:s'), '1981-03-07 08:30:00');
        self::assertEquals($userProfileDto->gender, UserGenderEnum::MALE);

        /** Create UserProfile and UserProfileEvent via Dto */
        $UserProfile = $handler->handle($userProfileDto);

        /** get UserProfile and UserProfileEvent from database */
        $userProfileRepo      = $em->getRepository(UserProfile::class);
        $userProfileEventRepo = $em->getRepository(UserProfileEvent::class);
        $userProfileRoot      = $userProfileRepo->findOneBy(['id' => $UserProfile->getId()]);
        $userProfileEvent     = $userProfileEventRepo->findOneBy(['id' => $userProfileRoot->getEvent()]);

        self::assertInstanceOf(UserProfileEvent::class, $userProfileEvent);
        self::assertInstanceOf(UserProfile::class, $userProfileRoot);
        self::assertEquals($UserProfile, $userProfileRoot);

        self::assertTrue(true);
    }
}
