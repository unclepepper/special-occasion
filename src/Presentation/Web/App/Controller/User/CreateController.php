<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller\User;

use App\Domain\User\Entity\User;
use App\Domain\UserProfile\Dto\UserProfileNewDto;
use App\Domain\UserProfile\Entity\UserProfile;
use App\Domain\UserProfile\Enum\UserGenderEnum;
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
        UserProfileHandler $userProfileHandler
    ): Response {
        $userProfileDto = new UserProfileNewDto();

        $userProfileDto->event    = '0195c998-b1e7-7bc9-a8b9-382b13f5d9da';
        $userProfileDto->profile  = new UserProfile();
        $userProfileDto->username = 'Michael';
        $userProfileDto->birthday = new DateTimeImmutable('2025-03-24');
        $userProfileDto->gender   = UserGenderEnum::MALE;

        $userProfileHandler->handle($userProfileDto);

        $newUser = new User();

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $this->redirectToRoute('app.index', ['id' => $newUser->getId()]);
    }
}
