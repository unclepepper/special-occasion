<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserProfile;

use App\Domain\UserProfile\Dto\UserProfileDto;
use App\Domain\UserProfile\Dto\UserProfileInfoDto;
use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Info\UserProfileInfo;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Enum\UserStatusEnum;
use App\Domain\UserProfile\UseCase\New\UserProfileHandler;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

/**
 * @internal
 *
 * @coversNothing
 */
class NewUserProfileTest extends KernelTestCase
{
    /**
     * @throws ReflectionException
     * @throws ClassNotFoundException
     */
    public function testNewUserProfile(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        /** @var UserProfileHandler $handler */
        $handler = self::getContainer()->get(UserProfileHandler::class);

        $userProfileInfoDto          = new UserProfileInfoDto();
        $userProfileInfoDto->name    = 'name-test';
        $userProfileInfoDto->surname = 'surname-test';
        $userProfileInfoDto->status  = UserStatusEnum::ACTIVE;


        /** Create Dto for UserProfile */
        $userProfileDto           = new UserProfileDto();
        $userProfileDto->username = 'TestUsername';
        $userProfileDto->birthday = new DateTimeImmutable('1981-03-07 08:30:00');
        $userProfileDto->gender   = UserGenderEnum::MALE;
        $userProfileDto->info = $userProfileInfoDto;

        self::assertEquals('TestUsername', $userProfileDto->username);
        self::assertEquals('1981-03-07 08:30:00', $userProfileDto->birthday->format('Y-m-d H:i:s'));
        self::assertEquals(UserGenderEnum::MALE, $userProfileDto->gender);


        self:: assertEquals('name-test', $userProfileDto->info->name);
        self:: assertEquals('surname-test', $userProfileDto->info->surname);
        self:: assertEquals( UserStatusEnum::ACTIVE, $userProfileDto->info->status);
        self:: assertEquals($userProfileInfoDto, $userProfileDto->info);

        /** Create UserProfile and UserProfileEvent via Dto */
        $UserProfile = $handler->handle($userProfileDto);

        /** get UserProfile and UserProfileEvent from database */
        $userProfileRepo      = $em->getRepository(UserProfile::class);
        $userProfileEventRepo = $em->getRepository(UserProfileEvent::class);
        $userProfileRoot      = $userProfileRepo->findOneBy(['id' => $UserProfile->getId()]);
        $userProfileEvent     = $userProfileEventRepo->findOneBy(['id' => $userProfileRoot->getEvent()]);
        $userProfileInfoRepo    = $em->getRepository(UserProfileInfo::class);
        $userProfileInfo  = $userProfileInfoRepo->findOneBy(['event' =>$userProfileEvent->getId()]);


        self::assertInstanceOf(UserProfileInfo::class, $userProfileInfo);
        self::assertInstanceOf(UserProfileEvent::class, $userProfileEvent);
        self::assertInstanceOf(UserProfile::class, $userProfileRoot);
        self::assertEquals($UserProfile, $userProfileRoot);



        /** Create Dto for update UserProfile */
        $userProfileInfoDtoEdit          = new UserProfileInfoDto();
        $userProfileInfoDtoEdit->name    = 'name-second-test';
        $userProfileInfoDtoEdit->surname = 'surname-second-test';
        $userProfileInfoDtoEdit->status  = UserStatusEnum::INACTIVE;


        $userProfileDtoEdit           = new UserProfileDto();
        $userProfileDtoEdit->event    = $userProfileRoot->getEvent();
        $userProfileDtoEdit->username = 'TestUsername-2';
        $userProfileDtoEdit->gender   = UserGenderEnum::FEMALE;
        $userProfileDtoEdit->info = $userProfileInfoDtoEdit;

        /** Create UserProfile and UserProfileEvent via Dto */
        $handler->handle($userProfileDtoEdit);


        $userProfileRepoEdit     = $em->getRepository(UserProfile::class);
        $userProfileEdit = $userProfileRepoEdit->findOneBy(['id' => $userProfileRoot->getId()]);
        var_dump($userProfileEdit);

        self::assertTrue(true);
    }
}
