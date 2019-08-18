<?php

namespace App\Factory;

use App\Form\AreaMenuSelectType;
use App\Helper\RessourceSelectedHelper;
use App\Navigation\MenuSelect;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

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

        $menuSelect = MenuSelectFactory::createNew();
        $menuSelect->setArea($area);
        $menuSelect->setRoom($room);

        return $this->formFactory->create(AreaMenuSelectType::class, $menuSelect);
    }
}
