<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('//manager')]
class ManagerController extends AbstractController
{
    #[Route('/', name: 'liste_manager')]
    public function indexRole(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(User::class);
        $gestionnaires=$manager->findAll();
        return $this->render('manager/index.html.twig', [
            'gestionnaires' => $gestionnaires,
        ]);
    }
}
