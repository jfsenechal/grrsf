<?php

namespace App\Form;

use App\Entity\GrrUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrUtilisateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('password')
            ->add('email')
            ->add('statut')
            ->add('etat')
            ->add('defaultSite')
            ->add('defaultArea')
            ->add('defaultRoom')
            ->add('defaultStyle')
            ->add('defaultListType')
            ->add('defaultLanguage')
            ->add('source')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrrUtilisateur::class,
        ]);
    }
}
