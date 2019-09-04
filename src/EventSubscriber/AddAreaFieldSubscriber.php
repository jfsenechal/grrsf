<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/03/19
 * Time: 22:00.
 */

namespace App\EventSubscriber;

use App\Form\Type\AreaHiddenType;
use App\Form\Type\AreaSelectType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddAreaFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $required;

    public function __construct(bool $required = false)
    {
        $this->required = $required;
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
        /**
         * @var FormInterface
         */
        $form = $event->getForm();

        $options = [
            'required' => $this->required,
        ];

        if (!$this->required) {
            $options['placeholder'] = 'area.form.select.placeholder';
        }

        if ($area) {
            $form->add('area', AreaHiddenType::class);
        } else {
            $form->add(
                'area',
                AreaSelectType::class,
                    $options,
            );
        }
    }
}
