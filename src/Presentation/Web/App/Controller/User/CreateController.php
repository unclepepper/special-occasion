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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('/create', name: 'app.create')]
    public function index(
        EntityManagerInterface $entityManager,
        UserProfileHandler $userProfileHandler
    ): Response {
        $userProfileDto           = new UserProfileNewDto();
        $userProfileDto->event    = '0195c8e7-f9e4-7154-8afe-f85149158d3f';
        $userProfileDto->profile  =  new UserProfile();
        $userProfileDto->username = null;
        $userProfileDto->birthday = new DateTimeImmutable('10-11-2025');
        $userProfileDto->gender   = UserGenderEnum::MALE;

        $userProfileHandler->handle($userProfileDto);

        $newUser = new User();

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $this->redirectToRoute('app.index', ['id' => $newUser->getId()]);
    }
}
