<?php

namespace App\Form;

use App\Entity\User;
use App\GrrData\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @var UserData
     */
    private $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'label' => 'user.form.nom.label',
                ]
            )
            ->add(
                'prenom',
                TextType::class,
                [
                    'label' => 'user.form.prenom.label',
                ]
            )
            ->add(
                'login',
                TextType::class,
                [
                    'label' => 'user.form.username.label',
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'user.form.password.label'],
                    'second_options' => ['label' => 'user.form.passwordrepeat.label'],
                ]
            )
            ->add('email', EmailType::class, [
                    'label' => 'user.form.email.label',
                ])
            ->add(
                'statut',
                ChoiceType::class,
                [
                    'choices' => array_flip($this->userData->statutsList()),
                    'label' => 'user.form.statut.label',
                ]
            )
            ->add(
                'etat',
                ChoiceType::class,
                [
                    'choices' => array_flip($this->userData->etatsList()),
                    'label' => 'user.form.etat.label',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
