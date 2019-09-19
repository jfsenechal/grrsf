<?php

namespace App\Handler;

use App\Entity\Entry;
use App\Manager\EntryManager;
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
     * @var Security
     */
    private $security;
    /**
     * @var HandlerPeriodicity
     */
    private $handlerPeriodicity;

    public function __construct(
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        HandlerPeriodicity $handlerPeriodicity,
        Security $security
    ) {
        $this->entryRepository = $entryRepository;
        $this->entryManager = $entryManager;
        $this->security = $security;
        $this->handlerPeriodicity = $handlerPeriodicity;
    }

    public function handleNewEntry(FormInterface $form, Entry $entry)
    {
        $data = $form->getData();

        $this->setUserAdd($entry);
        $this->fullDay($entry);
        $periodicity = $entry->getPeriodicity();

        if ($periodicity) {
            if (null === $periodicity->getType()) {
                $entry->setPeriodicity(null);
            }
        }

        $this->entryManager->insert($entry);
        $this->handlerPeriodicity->handleNewPeriodicity($entry);
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
