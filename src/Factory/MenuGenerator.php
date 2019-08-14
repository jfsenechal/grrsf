<?php

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Room;
use App\Form\AreaMenuSelectType;
use App\Helper\RessourceSelectedHelper;
use App\Navigation\MenuSelect;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MenuGenerator
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RessourceSelectedHelper
     */
    private $ressourceSelectedHelper;

    public function __construct(FormFactoryInterface $formFactory, RessourceSelectedHelper $ressourceSelectedHelper)
    {
        $this->formFactory = $formFactory;
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
    }

    public function generateMenuSelect(): FormInterface
    {
        $area =$this->ressourceSelectedHelper->getArea();
        $room =$this->ressourceSelectedHelper->getRoom();

        $menuSelect = new MenuSelect();
        $menuSelect->setArea($area);

        if ($room !== null) {
            $menuSelect->setRoom($room);
        }

        return $this->formFactory->create(AreaMenuSelectType::class, $menuSelect);
    }
}
