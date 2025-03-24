<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserProfile;

use App\Domain\UserProfile\Dto\UserProfileNewDto;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Type\UserProfile\UserProfileUid;
use App\Domain\UserProfile\UseCase\New\UserProfileHandler;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class NewUserProfileTest extends KernelTestCase
{
    public const string USER_PROFILE_TEST_ID = '889cf12b-3f2f-486b-916b-7f5eaa97f43b';

    public function testNewUser(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $handler = self::getContainer()->get(UserProfileHandler::class);

        $userProfileDto = new UserProfileNewDto();
        $userProfileDto->event = null;
        $userProfileDto->profile =  new UserProfile(self::USER_PROFILE_TEST_ID)->getId();
        $userProfileDto->username = 'Nikolay';
        $userProfileDto->birthday = new DateTimeImmutable();
        $userProfileDto->gender = UserGenderEnum::MALE;


        $handler->handle($userProfileDto);

        self::assertTrue(true);
    }
}
