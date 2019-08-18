<?php

namespace App\Form\Security;

use App\EventSubscriber\AddAreaFieldSubscriber;
use App\EventSubscriber\AddRoomsFieldSubscriber;
use App\EventSubscriber\AddUserFieldSubscriber;
use App\Model\UserManagerResourceModel;
use App\Repository\Security\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserManagerResourceType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            1 => 'usermanager.form.area_level.administrator.label',
            2 => 'usermanager.form.area_level.manager.label',
        ];
        $builder
            ->add(
                'area_level',
                ChoiceType::class,
                [
                    'label' => 'usermanager.form.area_level.label',
                    'help' => 'usermanager.form.area_level.help',
                    'choices' => array_flip($choices),
                    'placeholder' => 'None',
                    'required' => false,
                    'expanded' => true,
                ]
            )
            ->addEventSubscriber(new AddAreaFieldSubscriber())
            ->addEventSubscriber(new AddRoomsFieldSubscriber())
            ->addEventSubscriber(new AddUserFieldSubscriber($this->userRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserManagerResourceModel::class,
            ]
        );
    }
}
