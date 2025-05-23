<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller\User;

use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends AbstractController
{
    #[Route('/delete/{id}', name: 'app.delete')]
    public function index(
        UserRepositoryInterface $userRepository,
        EntityManagerInterface $entityManager,
        string $id
    ): Response {
        $user = $userRepository->findUserById($id);
        if (null !== $user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.index');
    }
}
