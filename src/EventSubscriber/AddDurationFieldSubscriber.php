<?php


namespace App\EventSubscriber;


use App\Entity\Entry;
use App\Form\Type\DurationTimeTypeField;
use App\GrrData\EntryData;
use App\Validator\Duration;
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
            $duration = $this->getDuration($entry);
            $form->add(
                'duration',
                DurationTimeTypeField::class,
                [
                    'label' => false,
                    'data' => $duration,
                    'constraints' => [
                        new Duration(),
                    ],
                ]
            );
            $form->get('duration')
            ->addModelTransformer($this->transformer);
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
     * Ajoute la durée suivant l'unité de temps choisi
     * @param FormEvent $event
     * @throws \Exception
     */
    public function OnPreSubmit(FormEvent $event)
    {
        $entry = $event->getData();
        $room = $entry->getRoom();

        $type = $room ? $room->getTypeAffichageReser() : 0;

        if ($type === 0) {
            /**
             * @var Entry $entry
             */
            $entry = $event->getData();

            $form = $event->getForm();
            $duration = $form->getData()->getDuration();

            $startTime = Carbon::instance($entry->getStartTime());

            $unit = $duration['unit'];
            $time = $duration['time'];

            switch ($unit) {
                case EntryData::UNIT_TIME_WEEKS:
                    $startTime->addWeeks($time);
                    break;
                case EntryData::UNIT_TIME_DAYS:
                    $startTime->addDays($time);
                    break;
                case EntryData::UNIT_TIME_HOURS:
                    $startTime->addHours($time);
                    break;
                case EntryData::UNIT_TIME_MINUTES:
                    $startTime->addMinutes($time);
                    break;
                default:
                    throw new \Exception('Unexpected value');
            }
        }
    }

    private function getDuration(Entry $entry)
    {
        $duration = [];
        if ($entry || null != $entry->getId()) {

            $startTime = Carbon::instance($entry->getStartTime());
            $endTime = Carbon::instance($entry->getEndTime());

            $minutes = $startTime->diffInMinutes($endTime);
            $hours = $startTime->diffInRealHours($endTime);
            $days = $startTime->diffInDays($endTime);
            $weeks = $startTime->diffInWeeks($endTime);

            if ($minutes > 0) {
                $duration['unit'] = EntryData::UNIT_TIME_MINUTES;
                $duration['time'] = $minutes;
            }
            if ($hours > 0) {
                $duration['unit'] = EntryData::UNIT_TIME_HOURS;
                $duration['time'] = $hours.'.'.($minutes - $hours * 60);
            }
            if ($days > 0) {
                $duration['unit'] = EntryData::UNIT_TIME_DAYS;
                $duration['time'] = $days;
            }
            if ($weeks > 0) {
                $duration['unit'] = EntryData::UNIT_TIME_WEEKS;
                $duration['time'] = $weeks;
            }
        }

        return $duration;
    }
}