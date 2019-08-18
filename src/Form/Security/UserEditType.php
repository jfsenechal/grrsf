<?php

namespace App\Form\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Form\Type\AreaSelectType;
use App\Form\Type\RoomSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'email',
                EmailType::class,
                [

                ]
            )
            ->add(
                'area_default',
                AreaSelectType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'room_default',
                RoomSelectType::class,
                [
                    'label' => 'user.form.room.label',
                    'required' => false,
                    'area' => null,
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
