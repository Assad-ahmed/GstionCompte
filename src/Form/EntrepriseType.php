<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Propriete;
use App\Entity\TypeEntreprise;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('ninea')
            ->add('chifAffaire')
            ->add('responsable')
            ->add('typeEntreprise', EntityType::class,[
                'expanded'=>false,

                'class'=>TypeEntreprise::class,
                'multiple'=>false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.type', 'ASC');
                },
                'choice_label' => 'type',

                'attr' => [
                    'class'=> 'select2'
                ],
                'placeholder'=> ' Type Entreprise '
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
