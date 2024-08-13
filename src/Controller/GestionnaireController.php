<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\TacheType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestionnaire')]
class GestionnaireController extends AbstractController
{
    #[Route('/', name: 'liste_gestionnaire')]
    public function indexRole(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(User::class);
        $gestionnaires=$manager->findAll();
        return $this->render('gestionnaire/index.html.twig', [
            'gestionnaires' => $gestionnaires,
        ]);
    }

    #[Route('/edit{id?0}', name: 'edit_gestionnaire')]
    public function editRole(User $user=null,Request $request,ManagerRegistry $registry): Response
    {
        $new = false;
        if (!$user)
        {
            $new = true;
            $user = new User();

        }
        $form=$this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager=$registry->getManager();
            $manager->persist($user);
            $manager->flush();

            if ($new)
            {
                $message="a été ajouteé avec succes";
            }else{
                $message="a été modifieé avec succes";
            }
            $this->addFlash('success',$user->getLastName().$message);
            return $this->redirectToRoute('liste_gestionnaire');
        }else
        {
            return $this->render('gestionnaire/formulaire.html.twig',[
                'form'=>$form->createView()
            ]);
        }

    }

    #[Route('/delete/{id}', name: 'delete_gestionnaire')]
    public function deleteGestionnaire(User $user= null, ManagerRegistry $registry):Response
    {
        if($user)
        {
            $manager=$registry->getManager();
            $manager->remove($user);
            $manager->flush();
            $this->addFlash('success', "le gestionnaire a été supprimé avec success ");
        }else
        {
            $this->addFlash('errer', "le gestionnaire innexistante ");
        }
        return $this->redirectToRoute('liste_gestionnaire');
    }

}
