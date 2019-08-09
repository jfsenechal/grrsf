<?php

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Room;
use App\Form\AreaMenuSelectType;
use App\Navigation\MenuSelect;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MenuGenerator
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function generateMenuSelect(Area $area, Room $room = null): FormInterface
    {
        $menuSelect = new MenuSelect();
        $menuSelect->setArea($area);

        if ($room) {
            $menuSelect->setRoom($room);
        }

        return $this->formFactory->create(AreaMenuSelectType::class, $menuSelect);
    }
}
