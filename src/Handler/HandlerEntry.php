<?php

namespace App\Handler;

use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entity\PeriodicityDay;
use App\Manager\EntryManager;
use App\Manager\PeriodicityDayManager;
use App\Manager\PeriodicityManager;
use App\Periodicity\PeriodicityDaysProvider;
use App\Repository\EntryRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class HandlerEntry
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryManager
     */
    private $entryManager;
    /**
     * @var PeriodicityManager
     */
    private $periodicityManager;
    /**
     * @var PeriodicityDaysProvider
     */
    private $periodicityDaysProvider;
    /**
     * @var PeriodicityDayManager
     */
    private $periodicityDayManager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        PeriodicityManager $periodicityManager,
        PeriodicityDaysProvider $periodicityDaysProvider,
        PeriodicityDayManager $periodicityDayManager,
        Security $security
    ) {
        $this->entryRepository = $entryRepository;
        $this->entryManager = $entryManager;
        $this->periodicityManager = $periodicityManager;
        $this->periodicityDaysProvider = $periodicityDaysProvider;
        $this->periodicityDayManager = $periodicityDayManager;
        $this->security = $security;
    }

    public function handleNewEntry(FormInterface $form, Entry $entry)
    {
        $data = $form->getData();

        /**
         * @var Periodicity
         */
        $periodicity = $data->getPeriodicity();

        if (null === $periodicity->getType()) {
            $entry->setPeriodicity(null);
        } else {
            $days = $this->periodicityDaysProvider->getDaysByEntry($entry);
            foreach ($days as $day) {
                $periodicityDay = new PeriodicityDay();
                $periodicityDay->setDatePeriodicity($day->toImmutable());
                $periodicityDay->setEntry($entry);
                $this->periodicityDayManager->persist($periodicityDay);
            }
        }

        $this->setUserAdd($entry);
        $this->fullDay($entry);
        $this->entryManager->insert($entry);
        $this->periodicityDayManager->flush();
    }

    public function handleEditEntry(FormInterface $form, Entry $entry)
    {
        $data = $form->getData();
        $type = $data->getPeriodicity()->getType();
        if (null === $type) {
            $entry->setPeriodicity(null);
            //   $this->periodicityManager->remove($periodicity);
            // $this->periodicityManager->flush();
        }
        $this->entryManager->flush();
    }

    protected function fullDay(Entry $entry)
    {
        $duration = $entry->getDuration();
        if ($duration) {
            if ($duration->isFullDay()) {
                $area = $entry->getArea();
                $hourStart = $area->getStartTime();
                $hourEnd = $area->getEndTime();

                $entry->getStartTime()->setTime($hourStart, 0);
                $entry->getEndTime()->setTime($hourEnd, 0);
            }
        }
    }

    protected function setUserAdd(Entry $entry)
    {
        $user = $this->security->getUser();
        $username = isset($user) ? $user->getUsername() : null;
        $entry->setCreatedBy($username);
    }

    public function handleDeleteEntry(Entry $entry)
    {
        $this->entryManager->remove($entry);
        $this->entryManager->flush();
    }
}
