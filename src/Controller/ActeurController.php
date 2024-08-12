<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Tache;
use App\Form\ActeurType;
use App\Form\TacheType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/acteur')]
class ActeurController extends AbstractController
{
    #[Route('/', name: 'liste_acteur')]
    public function indexActeur(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(Acteur::class);
        $acteurs=$manager->findAll();
        return $this->render('acteur/index.html.twig', [
            'acteurs' => $acteurs,
        ]);
    }

    #[Route('/edit{id?0}', name: 'edit_acteur')]
    public function editActeur(Acteur $acteur=null,Request $request,ManagerRegistry $registry): Response
    {
        $new = false;
        if (!$acteur)
        {
            $new = true;
            $acteur = new Acteur();

        }
        $form=$this->createForm(ActeurType::class,$acteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager=$registry->getManager();
            $manager->persist($acteur);
            $manager->flush();

            if ($new)
            {
                $message="a été ajouteé avec succes";
            }else{
                $message="a été modifieé avec succes";
            }
            $this->addFlash('success',$acteur->getNom().$message);
            return $this->redirectToRoute('liste_acteur');
        }else
        {
            return $this->render('acteur/edit.html.twig',[
                'form'=>$form->createView()
            ]);
        }

    }

    #[Route('/delete/{id}', name: 'delete_acteur')]
    public function deleteActeur(Acteur $acteur= null, ManagerRegistry $registry):Response
    {
        if($acteur)
        {
            $manager=$registry->getManager();
            $manager->remove($acteur);
            $manager->flush();
            $this->addFlash('success', "l'acteur a été supprimé avec success ");
        }else
        {
            $this->addFlash('errer', "l'acteur est innexistante ");
        }
        return $this->redirectToRoute('liste_acteur');
    }
}
