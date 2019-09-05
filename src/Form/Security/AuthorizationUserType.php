<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 5/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Form\Type\AreaHiddenType;
use App\Form\Type\AreaSelectType;
use App\Model\AuthorizationAreaModel;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AuthorizationUserType extends AbstractType
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'area',
            AreaSelectType::class,
            [
                'placeholder' => 'area.form.select.placeholder',
            ]
        );

        $formModifier = function (FormInterface $form, Area $area = null) {

            $options = [
                'class' => Room::class,
                'label' => 'room.form.select.multiple.label',
                'placeholder' => '',
                'attr' => ['class' => 'custom-select my-1 mr-sm-2 room-select'],
                'multiple' => true,
            ];

            if ($area) {
                $options['query_builder'] = function (RoomRepository $roomRepository) use ($area) {
                    return $roomRepository->getRoomsByAreaQueryBuilder($area);
                };
            } else {
                $options['choices'] = [];
            }

            $form->add(
                'rooms',
                EntityType::class,
                $options
            );

        };

        /**
         * Sert à valider les ressources sélectionnées lors de l'envoie du form
         * Nécessaire car à l'init du form, la liste est vide.
         */
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getArea());
            }
        );

        $builder->get('area')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $area = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $area);
            }
        );
    }

    public function getParent()
    {
        return AuthorizationType::class;
    }

}