<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestionnaire/tache')]
class TacheController extends AbstractController
{
    #[Route('/', name: 'liste_tache')]
    public function indexRole(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(Tache::class);
        $taches=$manager->findAll();
        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/edit{id?0}', name: 'edit_tache')]
    public function editTache(Tache $tache=null, Request $request, ManagerRegistry $registry): Response
    {
        $new = false;
        if (!$tache)
        {
            $new = true;
            $tache = new Tache();

        }
        $form=$this->createForm(TacheType::class,$tache);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager=$registry->getManager();
            $manager->persist($tache);
            $manager->flush();

            if ($new)
            {
                $message="a été ajouteé avec succes";
            }else{
                $message="a été modifieé avec succes";
            }
            $this->addFlash('success',$tache->getNom().$message);
            return $this->redirectToRoute('liste_tache');
        }else
        {
            return $this->render('tache/edit.html.twig',[
                'form'=>$form->createView()
            ]);
        }

    }

    #[Route('/delete/{id}', name: 'delete_tache')]
    public function deleteTache(Tache $tache= null, ManagerRegistry $registry):Response
    {
        if($tache)
        {
            $manager=$registry->getManager();
            $manager->remove($tache);
            $manager->flush();
            $this->addFlash('success', "le tache a été supprimé avec success ");
        }else
        {
            $this->addFlash('errer', "le tache est innexistante ");
        }
        return $this->redirectToRoute('liste_tache');
    }

}
