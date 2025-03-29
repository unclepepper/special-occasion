<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller\User;

use App\Domain\User\Entity\User;
use App\Domain\UserProfile\Dto\UserProfileDto;
use App\Domain\UserProfile\Dto\UserProfileInfoDto;
use App\Domain\UserProfile\Enum\UserGenderEnum;
use App\Domain\UserProfile\Enum\UserStatusEnum;
use App\Domain\UserProfile\UseCase\New\UserProfileHandler;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    /**
     * @throws ReflectionException
     */
    #[Route('/create', name: 'app.create')]
    public function index(
        EntityManagerInterface $entityManager,
        UserProfileHandler $userProfileHandler,
    ): Response {
        $newUser = new User();

        $entityManager->persist($newUser);
        $entityManager->flush();

        $userProfileInfo          = new UserProfileInfoDto();
        $userProfileInfo->name    = 'name-test';
        $userProfileInfo->surname = 'surname-test';
        $userProfileInfo->status  = UserStatusEnum::ACTIVE;

        $userProfileDto  = new UserProfileDto();

        $userProfileDto->username = 'TestUsername-test';
        $userProfileDto->birthday = new DateTimeImmutable('1981-03-07 08:30:00');
        $userProfileDto->gender   = UserGenderEnum::MALE;
        $userProfileDto->info     = $userProfileInfo;

        $userProfileHandler->handle($userProfileDto);

        return $this->redirectToRoute('app.index', ['id' => $newUser->getId()]);
    }
}
