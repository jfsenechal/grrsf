<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 21:57
 */

namespace App\Form\Type;

use App\Entity\TypeArea;
use App\Repository\TypeAreaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryTypeField extends AbstractType
{
    /**
     * @var TypeAreaRepository
     */
    private $TypeAreaRepository;

    public function __construct(TypeAreaRepository $TypeAreaRepository)
    {
        $this->grrTypeAreaRepository = $TypeAreaRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => TypeArea::class,
            ]
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

}