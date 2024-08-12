<?php

namespace App\Controller;

use App\Repository\ActeurRepository;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manager')]
class RapportController extends AbstractController
{
    #[Route('/rapport/general',name: 'rapport_general', methods: "GET")]
    public function general(ActeurRepository $acteurRepository, EntrepriseRepository $entrepriseRepository): Response
    {
        // Récupérer les données nécessaires
        $entreprises = $entrepriseRepository->findAll();
        $acteurs = $acteurRepository->findAll();

        // Logique pour traiter et formater les données du rapport général

        return $this->render('rapport/general.html.twig', [
            'entreprises' => $entreprises,
            'acteurs' => $acteurs,
        ]);
    }

    #[Route('/rapport/acteurs-incomplets',name: 'rapport_acteurs_incomplets', methods: "GET")]
    public function acteursIncomplets(ActeurRepository $acteurRepository): Response
    {
        // Récupérer tous les acteurs
        $acteurs = $acteurRepository->findAll();

        // Filtrer les acteurs ayant des rôles non accomplis
        $acteursIncomplets = [];
        foreach ($acteurs as $acteur) {
            foreach ($acteur->getRoles() as $role) {
                if (!$role->isEstComplet()) {
                    $acteursIncomplets[] = $acteur;
                    break; // Un acteur est ajouté une seule fois même s'il a plusieurs rôles incomplets
                }
            }
        }

        return $this->render('rapport/acteurs_incomplets.html.twig', [
            'acteursIncomplets' => $acteursIncomplets,
        ]);
    }

    /**
     * @Route("/rapport/entreprises-acteurs-incomplets", name="rapport_entreprises_acteurs_incomplets", methods={"GET"})
     */
    public function entreprisesActeursIncomplets(ActeurRepository $acteurRepository): Response
    {
        $acteursIncomplets = $acteurRepository->findByRoleIncomplete();

        return $this->render('rapport/entreprises_acteurs_incomplets.html.twig', [
            'acteursIncomplets' => $acteursIncomplets,
        ]);
    }
}
