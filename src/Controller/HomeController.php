<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\ActeurRepository;
use App\Repository\EntrepriseRepository;
use App\Service\AlerteService;
use App\Service\StatutProcessusService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    public function __construct(private StatutProcessusService $statutProcessusService, private AlerteService $alerteService)
    {
    }
    #[Route('/', name: 'app_home')]
    public function index(ActeurRepository $acteurRepository,ManagerRegistry $registry): Response
    {
        $statutProcessus = $this->statutProcessusService->getStatutProcessus();
        $alertes = $this->alerteService->getIncompleteRoles();
        $manager=$registry->getRepository(Acteur::class);
        $acteurs=$manager->findAll();
        // Autres données nécessaires pour la vue

        return $this->render('home/index.html.twig', [
            'statutProcessus'=> $statutProcessus,
            'acteurs'=>$acteurs,
             'alertes' => $alertes,
            // Autres variables passées à la vue
        ]);
    }
    #[Route('/alerts', name: 'alerts')]
    public function indexP(): Response
    {
        $alerts = $this->alerteService->getIncompleteRoles();

        return $this->render('home/alertes.html.twig', [
            'alerts' => $alerts,
        ]);
    }

    /**
     * @Route("/acteur/{id}", name="acteur_detail")
     */
    public function acteurDetail($id, ActeurRepository $acteurRepository,ManagerRegistry $registry): Response
    {
        $acteur= $acteurRepository->find($id);
        $manager=$registry->getRepository(Acteur::class);
        $acteurs=$manager->findAll();

        if (!$acteur) {
            throw $this->createNotFoundException('Acteur non trouvée');
        }

        return $this->render('home/detail.html.twig', [
            'acteur' => $acteur,
            'acteurs' => $acteurs,
        ]);
    }

    /**
     * @Route("/entreprise/{id}", name="entreprise_modifier")
     */
    public function EntrepriseEdit($id, EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise non trouvé');
        }

        // Ajoutez la logique pour éditer l'acteur

        return $this->render('home/entreprise_edit.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/rapport/general", name="rapport_general", methods={"GET"})
     */
    public function general(): Response
    {
        // Logique pour traiter et formater les données du rapport général
        return $this->render('rapport/general.html.twig');
    }

}
