<?php


namespace App\Form\Type;

use App\Entity\Area;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'label' => 'room.form.select.label',
                    'help' => 'room.form.select.help',
                    'class' => Room::class,
                    'required' => false,
                    'placeholder' => 'room.form.select.placeholder',
                    'attr' => ['class' => 'custom-control custom-checkbox my-1 mr-sm-2'],
                ]
            );
        //    ->setRequired('area');
        //->setAllowedTypes('area', [Area::class, 'null']);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}