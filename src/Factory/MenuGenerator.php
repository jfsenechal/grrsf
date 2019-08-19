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
    /**
     * @var MenuSelectFactory
     */
    private $menuSelectFactory;

    public function __construct(
        MenuSelectFactory $menuSelectFactory,
        FormFactoryInterface $formFactory,
        RessourceSelectedHelper $ressourceSelectedHelper
    ) {
        $this->formFactory = $formFactory;
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
        $this->menuSelectFactory = $menuSelectFactory;
    }

    public function generateMenuSelect(): FormInterface
    {
        $area = $this->ressourceSelectedHelper->getArea();
        $room = $this->ressourceSelectedHelper->getRoom();

        $menuSelect = $this->menuSelectFactory->createNew();
        $menuSelect->setArea($area);
        $menuSelect->setRoom($room);

        return $this->formFactory->create(AreaMenuSelectType::class, $menuSelect);
    }
}
