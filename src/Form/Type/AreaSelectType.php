<?php

namespace App\Form\Type;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Area;
use App\Repository\AreaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'label' => 'area.form.select.label',
                'class' => Area::class,
                'query_builder' => function (AreaRepository $areaRepository): QueryBuilder {
                    return $areaRepository->getQueryBuilder();
                },
                'attr' => ['class' => 'custom-select my-1 mr-sm-2 ajax-select-room'],
                'invalid_message' => 'The selected area does not exist',
            ]
        );
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
