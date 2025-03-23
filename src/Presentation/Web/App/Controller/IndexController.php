<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller;

use App\Domain\Users\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/{id}', name: 'app.index')]
    public function index(UserRepositoryInterface $userRepository, ?string $id = null): Response
    {
        $user = null;
        if (null !== $id) {
            $user = $userRepository->findUserById($id);
        }

        $users = $userRepository->findAll();

        return $this->render('index/index.html.twig', [
            'controller_name'  => 'IndexController',
            'user'             => $user,
            'users'            => $users,
        ]);
    }
}
