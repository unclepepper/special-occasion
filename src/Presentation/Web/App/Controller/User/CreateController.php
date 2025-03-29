<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller\User;

use App\Domain\User\Entity\User;
use App\Domain\UserProfile\UseCase\New\UserProfileHandler;
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

        return $this->redirectToRoute('app.index', ['id' => $newUser->getId()]);
    }
}
