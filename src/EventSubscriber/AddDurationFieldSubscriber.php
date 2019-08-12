<?php


namespace App\EventSubscriber;

use App\Entity\Entry;
use App\Factory\DurationFactory;
use App\Form\Type\DurationTimeTypeField;
use App\Model\DurationModel;
use App\Validator\Duration;
use App\Validator\Duration as DurationConstraint;
use Carbon\Carbon;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddDurationFieldSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'OnPreSetData',
            FormEvents::POST_SUBMIT => 'OnPreSubmit',
        );
    }

    /**
     * Verifie si nouveau objet
     * Remplis les champs jours, heures, minutes
     * donne le bon label au submit
     *
     * @param FormEvent $event
     */
    public function OnPreSetData(FormEvent $event)
    {
        /**
         * @var Entry $entry
         */
        $entry = $event->getData();
        $form = $event->getForm();
        $room = $entry->getRoom();
        $type = $room ? $room->getTypeAffichageReser() : 0;

        if ($type === 0) {

            $duration = DurationFactory::createByEntry($entry);

            $form->add(
                'duration',
                DurationTimeTypeField::class,
                [
                    'label' => false,
                    'data' => $duration,
                    'constraints' => [
                        new DurationConstraint(),
                    ],
                ]
            );
        }

        if ($type === 1) {
            $form->add(
                'endTime',
                DateTimeType::class,
                [
                    'label' => 'entry.form.endTime.label',
                    'help' => 'entry.form.endTime.help',
                ]
            );
        }

    }

    /**
     * Modifie la date de fin de réservation suivant les données de la Duration
     * @param FormEvent $event
     * @throws \Exception
     */
    public function OnPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        /**
         * @var DurationModel $duration
         */
        $duration = $form->getData()->getDuration();

        if ($duration) {
            /**
             * @var Entry $entry
             */
            $entry = $event->getData();

            $startTime = Carbon::instance($entry->getStartTime());
            $endTime = $startTime->copy();

            $unit = $duration->getUnit();
            $time = $duration->getTime();

            switch ($unit) {
                case DurationModel::UNIT_TIME_WEEKS:
                    $endTime->addWeeks($time);
                    break;
                case DurationModel::UNIT_TIME_DAYS:
                    $endTime->addDays($time);
                    break;
                case DurationModel::UNIT_TIME_HOURS:
                    $endTime->addHours($time);
                    break;
                case DurationModel::UNIT_TIME_MINUTES:
                    $endTime->addMinutes($time);
                    break;
                default:
                    throw new \Exception('Unexpected value');
            }
            $entry->setEndTime($endTime);
        }
    }

}