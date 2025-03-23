<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller;

use App\Domain\Users\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(UserRepositoryInterface $userRepository): Response
    {
        $user = $userRepository->findUserById('878caa79-e976-4c66-979f-1e3d96f6b584');

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'user'            => $user,
        ]);
    }
}
