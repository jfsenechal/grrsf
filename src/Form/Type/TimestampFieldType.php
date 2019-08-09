<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 21:33.
 */

namespace App\Form\Type;

use App\Form\DataTransformer\TimestampToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class TimestampFieldType extends AbstractType
{
    /**
     * @var TimestampToDateTimeTransformer
     */
    private $transformer;

    public function __construct(TimestampToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}
