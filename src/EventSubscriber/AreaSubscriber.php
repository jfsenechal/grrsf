<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AreaSubscriber implements EventSubscriberInterface
{
    public function onSecurityAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
           'security.authentication.failure' => 'onSecurityAuthenticationFailure',
        ];
    }
}
