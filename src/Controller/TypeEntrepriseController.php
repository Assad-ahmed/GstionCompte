<?php

namespace App\Controller;


use App\Entity\TypeEntreprise;
use App\Form\TypeEntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/entreprise')]
class TypeEntrepriseController extends AbstractController
{
    #[Route('', name: 'app_type_entreprise')]
    public function index(ManagerRegistry $registry): Response
    {
        $manager=$registry->getRepository(TypeEntreprise::class);
        $typeEntreprises=$manager->findAll();
        return $this->render('type_entreprise/index.html.twig', [
            'typeEntreprises' =>$typeEntreprises,
        ]);
    }

    #[Route('/edit{id?0}', name: 'edit_type_entreprise')]
    public function editType(TypeEntreprise $typeEntreprise=null, Request $request,ManagerRegistry $registry): Response
    {
        $new = false;
        if (!$typeEntreprise)
        {
            $new = true;
            $typeEntreprise = new TypeEntreprise();

        }
        $form=$this->createForm(TypeEntrepriseType::class,$typeEntreprise);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager=$registry->getManager();
            $manager->persist($typeEntreprise);
            $manager->flush();

            if ($new)
            {
                $message="a été ajouteé avec succes";
            }else{
                $message="a été modifieé avec succes";
            }
            $this->addFlash('success',$typeEntreprise->getType().$message);
            return $this->redirectToRoute('app_type_entreprise');
        }else
        {
            return $this->render('type_entreprise/edit.html.twig',[
                'form'=>$form->createView()
            ]);
        }

    }

    #[Route('/delete/{id}', name: 'delete_type')]
    public function deleteType(TypeEntreprise $typeEntreprise= null, ManagerRegistry $registry):Response
    {
        if($typeEntreprise)
        {
            $manager=$registry->getManager();
            $manager->remove($typeEntreprise);
            $manager->flush();
            $this->addFlash('success', "le type entreprise a été supprimé avec success ");
        }else
        {
            $this->addFlash('errer', "le type entreprise est innexistante ");
        }
        return $this->redirectToRoute('app_type_entreprise');
    }
}
