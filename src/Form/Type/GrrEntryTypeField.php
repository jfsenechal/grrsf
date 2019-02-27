<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 21:57
 */

namespace App\Form\Type;


use App\Entity\GrrTypeArea;
use App\Repository\GrrTypeAreaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrrEntryTypeField extends AbstractType
{
    /**
     * @var GrrTypeAreaRepository
     */
    private $grrTypeAreaRepository;

    public function __construct(GrrTypeAreaRepository $grrTypeAreaRepository)
    {
        $this->grrTypeAreaRepository = $grrTypeAreaRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => GrrTypeArea::class,
            ]
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

}