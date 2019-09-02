<?php

namespace App\Form\Type;

use App\Form\DataTransformer\AreaToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaHiddenType extends AbstractType
{
    private $transformer;

    public function __construct(AreaToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'invalid_message' => 'The selected area does not exist',
            ]
        );
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}
