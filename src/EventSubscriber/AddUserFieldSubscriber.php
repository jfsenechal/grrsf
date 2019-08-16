<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Entity\Room;
use App\Entity\Security\User;
use App\Repository\Security\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
        $area = $entry->getArea();
        $form = $event->getForm();
        $user = $entry->getUser();

        if ($user) {
            $form->add('user', HiddenType::class);
        } else {
            $form->add(
                'user',
                EntityType::class,
                [
                    'label' => 'entry.form.user.label',
                    'required' => false,
                    'class' => User::class,
                    'placeholder' => 'entry.form.user.select.placeholder',
                    'query_builder' => $this->userRepository->getQueryBuilder(),
                    'attr' => ['class' => 'custom-select my-1 mr-sm-2'],
                ]
            );
        }
    }
}
