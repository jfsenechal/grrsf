<?php


namespace App\Handler;


use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entity\PeriodicityDay;
use App\Manager\EntryManager;
use App\Manager\PeriodicityDayManager;
use App\Manager\PeriodicityManager;
use App\Provider\PeriodicityDaysProvider;
use App\Repository\EntryRepository;
use Symfony\Component\Form\FormInterface;

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

    public function __construct(
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        PeriodicityManager $periodicityManager,
        PeriodicityDaysProvider $periodicityDaysProvider,
        PeriodicityDayManager $periodicityDayManager
    ) {
        $this->entryRepository = $entryRepository;
        $this->entryManager = $entryManager;
        $this->periodicityManager = $periodicityManager;
        $this->periodicityDaysProvider = $periodicityDaysProvider;
        $this->periodicityDayManager = $periodicityDayManager;
    }

    public function handleNewEntry(FormInterface $form, Entry $entry)
    {
        $data = $form->getData();

        /**
         * @var Periodicity $periodicity
         */
        $periodicity = $data->getPeriodicity();

        if (null === $periodicity->getType()) {
            $entry->setPeriodicity(null);
        } else {
            $days = $this->periodicityDaysProvider->getDays($entry);
            foreach ($days as $day) {
                $periodicityDay = new PeriodicityDay();
                $periodicityDay->setDatePeriodicity($day->toImmutable());
                $periodicityDay->setEntry($entry);
                $this->periodicityDayManager->persist($periodicityDay);
            }
        }

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

    public function handleDeleteEntry(Entry $entry)
    {
        $this->entryManager->remove($entry);
        $this->entryManager->flush();
    }

}