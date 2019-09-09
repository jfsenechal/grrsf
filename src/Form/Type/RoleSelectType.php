<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 9/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $areaAdministrator = new \stdClass();
        $areaAdministrator->value = 1;
        $areaAdministrator->name = 'authorization.role.area.administrator.label';
        $areaAdministrator->description = 'authorization.role.area.administrator.help';

        $resourceAdministrator = new \stdClass();
        $resourceAdministrator->value = 2;
        $resourceAdministrator->name = 'authorization.role.resource.administrator.label';
        $resourceAdministrator->description = 'authorization.role.resource.administrator.help';

        $choices = [$areaAdministrator, $resourceAdministrator];

        $resolver->setDefaults(
            [
                'choices' => $choices,
                'label' => 'authorization.area.role.label',
                'placeholder' => 'none.male',
                'choice_label' => function ($role) {
                    return $role->name;
                },
                'choice_value' => function ($role) {
                    if ($role == null) {
                        return null;
                    }

                    return $role->value;
                },
                'description' => function ($role) {
                    return $role->description;
                },
                'required' => false,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'authorization_role']//for js
            ]
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }


    public function getParent()
    {
        return ChoiceType::class;
    }

}