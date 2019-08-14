<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'user.form.name.label',
                ]
            )
            ->add(
                'first_name',
                TextType::class,
                [
                    'label' => 'user.form.first_name.label',
                    'required' => false,
                ]
            )
            ->add(
                'area_default',
                EntityType::class,
                [
                    'label' => 'user.form.area.label',
                    'required' => false,
                    'class' => Area::class,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            )
            ->add(
                'room_default',
                EntityType::class,
                [
                    'label' => 'user.form.room.label',
                    'required' => false,
                    'class' => Room::class,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
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
