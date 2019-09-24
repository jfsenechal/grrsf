<?php

namespace App\Controller\Front;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Entry\HandlerEntry;
use App\Events\EntryEvent;
use App\Factory\EntryFactory;
use App\Form\EntryType;
use App\Form\EntryWithPeriodicityType;
use App\Form\Search\SearchEntryType;
use App\Periodicity\PeriodicityDaysProvider;
use App\Repository\EntryRepository;
use App\Router\FrontRouterHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/front/entry")
 */
class EntryController extends AbstractController
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryFactory
     */
    private $entryFactory;
    /**
     * @var PeriodicityDaysProvider
     */
    private $periodicityService;
    /**
     * @var HandlerEntry
     */
    private $handlerEntry;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var FrontRouterHelper
     */
    private $frontRouterHelper;

    public function __construct(
        EntryFactory $entryFactory,
        EntryRepository $entryRepository,
        PeriodicityDaysProvider $periodicityService,
        HandlerEntry $handlerEntry,
        EventDispatcherInterface $eventDispatcher,
        FrontRouterHelper $frontRouterHelper
    ) {
        $this->entryRepository = $entryRepository;
        $this->entryFactory = $entryFactory;
        $this->periodicityService = $periodicityService;
        $this->handlerEntry = $handlerEntry;
        $this->eventDispatcher = $eventDispatcher;
        $this->frontRouterHelper = $frontRouterHelper;
    }

    /**
     * @Route("/", name="grr_front_entry_index", methods={"GET", "POST"})
     * @IsGranted("grr.entry.index")
     */
    public function index(Request $request): Response
    {
        $args = $entries = [];

        $form = $this->createForm(SearchEntryType::class, $args);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entries = $this->entryRepository->search($args);
        }

        return $this->render(
            '@grr_front/entry/index.html.twig',
            [
                'entries' => $entries,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new/area/{area}/room/{room}/year/{year}/month/{month}/day/{day}/hour/{hour}/minute/{minute}", name="grr_front_entry_new", methods={"GET", "POST"})
     * @Entity("area", expr="repository.find(area)")
     * @Entity("room", expr="repository.find(room)")
     *
     * @param Request $request
     * @param Area $area
     * @param Room $room
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int|null $hour
     * @param int|null $minute
     *
     * @return Response
     *
     * @throws \Exception
     * @IsGranted("grr.addEntry", subject="room")
     */
    public function new(
        Request $request,
        Area $area,
        Room $room,
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute
    ): Response {
        $entry = $this->entryFactory->initEntryForNew($area, $room, $year, $month, $day, $hour, $minute);

        $entryEvent = new EntryEvent($entry);
        $this->eventDispatcher->dispatch($entryEvent, EntryEvent::NEW_INITIALIZE);

        $form = $this->createForm(EntryWithPeriodicityType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlerEntry->handleNewEntry($form, $entry);

            $entryEvent = new EntryEvent($entry);
            $this->eventDispatcher->dispatch($entryEvent, EntryEvent::NEW_SUCCESS);

            return $this->redirectToRoute(
                'grr_front_entry_show',
                [
                    'id' => $entry->getId(),
                ]
            );
        }

        return $this->render(
            '@grr_front/entry/new.html.twig',
            [
                'entry' => $entry,
                'displayOptionsWeek' => false,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_front_entry_show", methods={"GET"})
     * @IsGranted("grr.entry.show", subject="entry")
     */
    public function show(Entry $entry): Response
    {
        $urlList = $this->frontRouterHelper->generateMonthView($entry);
        $periodicities = [];
        if ($periodicity = $entry->getPeriodicity()) {
            $periodicities = $this->entryRepository->findByPeriodicity($periodicity);
        }

        return $this->render(
            '@grr_front/entry/show.html.twig',
            [
                'entry' => $entry,
                'periodicities' => $periodicities,
                'url_back' => $urlList,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_front_entry_edit", methods={"GET", "POST"})
     * @IsGranted("grr.entry.edit", subject="entry")
     */
    public function edit(Request $request, Entry $entry): Response
    {
        $entry->setArea($entry->getRoom()->getArea());

        if ($periodicity = $entry->getPeriodicity()) {
            $periodicity->setEntryReference($entry);//use for validator
        }

        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlerEntry->handleEditEntry();

            $entryEvent = new EntryEvent($entry);
            $this->eventDispatcher->dispatch($entryEvent, EntryEvent::EDIT_SUCCESS);

            return $this->redirectToRoute(
                'grr_front_entry_show',
                ['id' => $entry->getId()]
            );
        }

        return $this->render(
            '@grr_front/entry/edit.html.twig',
            [
                'entry' => $entry,
                'periodicities' => [],
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_front_entry_delete", methods={"DELETE"})
     * @IsGranted("grr.entry.delete", subject="entry")
     */
    public function delete(Request $request, Entry $entry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $this->handlerEntry->handleDeleteEntry($entry);

            $entryEvent = new EntryEvent($entry);
            $this->eventDispatcher->dispatch($entryEvent, EntryEvent::DELETE_SUCCESS);
        }

        return $this->redirectToRoute('grr_home');
    }
}
