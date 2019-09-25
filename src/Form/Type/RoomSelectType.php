<?php

namespace App\Form\Type;

use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'label' => 'room.form.select.label',
                    'class' => Room::class,
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2 room-select'],
                ]
            );
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
