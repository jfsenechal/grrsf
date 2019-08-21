<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Security\User;
use App\Repository\Security\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddUserFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        $entry = $event->getData();
        $form = $event->getForm();
        $user = $entry->getUsers();

        if ($user) {
        //    $form->add('user', HiddenType::class);
        } else {
            $form->add(
                'users',
                EntityType::class,
                [
                    'label' => 'entry.form.user.select.label',
                    'class' => User::class,
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => $this->userRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-control custom-checkbox my-1 mr-sm-2'],
                ]
            );
        }
    }
}
