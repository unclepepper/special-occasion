<?php

declare(strict_types=1);

namespace App\Presentation\Web\App\Controller\User;

use App\Domain\Users\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('/create', name: 'app.create')]
    public function index(
        EntityManagerInterface $entityManager
    ): Response {
        $newUser = new User();

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $this->redirectToRoute('app.index', ['id' => $newUser->getId()]);
    }
}
