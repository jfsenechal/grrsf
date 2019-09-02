<?php


namespace App\EventSubscriber;

use App\Entity\Entry;
use App\Factory\DurationFactory;
use App\Form\Type\DurationTimeTypeField;
use App\Model\DurationModel;
use App\Validator\Duration;
use App\Validator\Duration as DurationConstraint;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddDurationFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var DurationFactory
     */
    private $durationFactory;

    public function __construct(DurationFactory $durationFactory)
    {
        $this->durationFactory = $durationFactory;
    }

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
            FormEvents::SUBMIT => 'OnSubmit',
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

            $scale = 0;
            $duration = $this->durationFactory->createByEntry($entry);
            if ($duration->getUnit() == DurationModel::UNIT_TIME_HOURS) {
                $scale = 1;
            }

            $form->add(
                'duration',
                DurationTimeTypeField::class,
                [
                    'label' => false,
                    'data' => $duration,
                    'scale' => $scale,
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
    public function OnSubmit(FormEvent $event)
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

            $endTime = Carbon::instance($entry->getStartTime());

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
                    $minutes = $time * CarbonInterface::MINUTES_PER_HOUR;
                    $endTime->addMinutes($minutes);
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