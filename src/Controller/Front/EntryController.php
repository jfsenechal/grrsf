<?php

namespace App\Controller\Front;

use App\Entity\Entry;
use App\Factory\EntryFactory;
use App\Form\SearchEntryType;
use App\Manager\EntryManager;
use App\Repository\EntryRepository;
use App\Repository\RepeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var RepeatRepository
     */
    private $repeatRepository;
    /**
     * @var EntryManager
     */
    private $entryManager;
    /**
     * @var EntryFactory
     */
    private $entryFactory;

    public function __construct(
        EntryFactory $entryFactory,
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        RepeatRepository $repeatRepository
    ) {
        $this->entryRepository = $entryRepository;
        $this->repeatRepository = $repeatRepository;
        $this->entryManager = $entryManager;
        $this->entryFactory = $entryFactory;
    }

    /**
     * @Route("/", name="grr_front_entry_index", methods={"GET","POST"})
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
     * @Route("/{id}", name="grr_front_entry_show", methods={"GET"})
     */
    public function show(Entry $entry): Response
    {
        $repeat = '';
        if ($entry->getRepeatId()) {
            $repeat = $this->repeatRepository->find($entry->getRepeatId());
        }

        return $this->render(
            '@grr_front/entry/show.html.twig',
            [
                'entry' => $entry,
                'repeat' => $repeat,
            ]
        );
    }
}
