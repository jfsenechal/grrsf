<?php


namespace App\Form\Type;


use App\Entity\Area;
use App\Repository\AreaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label' => 'area.form.select.label',
                'help' => 'area.form.select.help',
                'class' => Area::class,
                'placeholder' => 'area.form.select.placeholder',
                'query_builder' => function (AreaRepository $areaRepository) {
                    return $areaRepository->getQueryBuilder();
                },
                'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                'invalid_message' => 'The selected area does not exist',
            ]
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }
}