<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 21:57
 */

namespace App\Form\Type;

use App\Entity\EntryType;
use App\Repository\EntryTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryTypeField extends AbstractType
{
    /**
     * @var EntryTypeRepository
     */
    private $TypeAreaRepository;

    public function __construct(EntryTypeRepository $TypeAreaRepository)
    {
        $this->TypeAreaRepository = $TypeAreaRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => EntryType::class,
            ]
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

}