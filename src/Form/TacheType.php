<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\Entreprise;
use App\Entity\Tache;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date_fin', DateType::class, [
                'label' => 'Date fin',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'label' => 'Commentaire',
            ])
            ->add('dateRealisation', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de réalisation',
            ])
            ->add('estComplet')
            ->add('acteur', EntityType::class, [
                'class' => Acteur::class,
                'choice_label' => 'nom',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
         ->add('dureeTache', TextType::class, [
        'label' => 'Durée de la tâche (ex: J-4, J+3)',
    ])
            ->add('Valider', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
