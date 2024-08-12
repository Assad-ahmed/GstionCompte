<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\TypeEntreprise;
use App\Form\EntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'app_entreprise')]
    public function index(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(Entreprise::class);
        $entreprises=$manager->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }
    /**
     * @Route("/entreprise/{id}", name="entreprise_show")
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }


    #[Route('/edit{id?0}', name: 'edit_entreprise')]
    public function editEntreprise(Entreprise $entreprise=null,Request $request,ManagerRegistry $registry): Response
    {
        $new = false;
        if (!$entreprise)
        {
            $new = true;
            $entreprise = new Entreprise();

        }
        $form=$this->createForm(EntrepriseType::class,$entreprise);
        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
             $manager=$registry->getManager();
             $manager->persist($entreprise);
             $manager->flush();

             if ($new)
             {
                 $message="a été ajouteé avec succes";
             }else{
                 $message="a été modifieé avec succes";
             }
           $this->addFlash('success',$entreprise->getNom().$message);
             return $this->redirectToRoute('app_entreprise');
         }else
         {
             return $this->render('entreprise/edit.html.twig',[
                 'form'=>$form->createView()
             ]);
         }

    }

    #[Route('/delete/{id}', name: 'delete_entreprise')]
    public function deleteEntreprise(Entreprise $entreprise= null, ManagerRegistry $registry):Response
    {
        if($entreprise)
        {
            $manager=$registry->getManager();
            $manager->remove($entreprise);
            $manager->flush();
            $this->addFlash('success', "l'entreprise entreprise a été supprimé avec success ");
        }else
        {
            $this->addFlash('errer', "l'entreprise entreprise est innexistante ");
        }
        return $this->redirectToRoute('app_entreprise');
    }
}
