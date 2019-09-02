<?php

namespace App\EventSubscriber;

use App\Controller\Front\FrontControllerInterface;
use App\Navigation\RessourceSelectedHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class RessourceSelectedSubscriber implements EventSubscriberInterface
{
    /**
     * @var RessourceSelectedHelper
     */
    private $ressourceSelectedHelper;

    public function __construct(RessourceSelectedHelper $ressourceSelectedHelper)
    {
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $controller = $event->getController();

        /**
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format.
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof FrontControllerInterface) {
            $area = $event->getRequest()->get('area');
            $room = $event->getRequest()->get('room');
            /*
             * if not set in url, force by user all ressources
             */
            if (!$room) {
                $room = -1;
            }

            if ($area) {
                $this->ressourceSelectedHelper->setSelected($area, $room);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
