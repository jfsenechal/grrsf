<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Security\User;
use App\Form\Type\AreaSelectType;
use App\Security\AuthorizationHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class AddAreaFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AuthorizationHelper
     */
    private $authorizationHelper;

    public function __construct(Security $security, AuthorizationHelper $authorizationHelper)
    {
        $this->security = $security;
        $this->authorizationHelper = $authorizationHelper;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException(
                'The EntryTypeForm cannot be used without an authenticated user!'
            );
        }

        $options = ['required' => true];

        $areas = $this->authorizationHelper->getAreasUserCanAdd($user);
        $options['choices'] = $areas;

        /**
         * @var FormInterface
         */
        $form = $event->getForm();

        $form->add(
            'area',
            AreaSelectType::class,
            $options
        );
    }
}
