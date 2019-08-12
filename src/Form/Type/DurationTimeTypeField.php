<?php

namespace App\Form\Type;

use App\GrrData\EntryData;
use App\Validator\Duration;
use App\Validator\DurationValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class DurationTimeTypeField extends AbstractType
{
    /**
     * @var EntryData
     */
    private $entryData;

    public function __construct(EntryData $entryData)
    {
        $this->entryData = $entryData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $this->entryData->getUnitsTime();

        $builder
            ->add(
                'time',
                NumberType::class,
                [
                    'label' => 'entry.form.duration_time.label',
                    'scale' => 2,
                ]
            )
            ->add(
                'unit',
                ChoiceType::class,
                [
                    'choices' => array_flip($choices),
                    //  'label' => 'entry.form.duration_unit.label',
                    'label' => ' ',
                    'help' => 'entry.form.duration_unit.help'                   ,
                ]
            )
            ->add(
                'full_day',
                CheckboxType::class,
                [
                    'label' => 'entry.form.full_day.label',
                    'help' => 'entry.form.full_day.help',
                    'required'=>false
                ]
            );
    }
}
