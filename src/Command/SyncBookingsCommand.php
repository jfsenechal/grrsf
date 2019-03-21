<?php

namespace App\Command;

use App\Booking\BookingService;
use App\Factory\EntryFactory;
use App\Manager\EntryManager;
use App\Repository\EntryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncBookingsCommand extends Command
{
    protected static $defaultName = 'SyncBookings';

    /**
     * @var BookingService
     */
    private $bookingService;
    /**
     * @var EntryFactory
     */
    private $entryFactory;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryManager
     */
    private $entryManager;

    public function __construct(
        ?string $name = null,
        BookingService $bookingService,
        EntryFactory $entryFactory,
        EntryRepository $entryRepository,
        EntryManager $entryManager
    ) {
        parent::__construct($name);
        $this->bookingService = $bookingService;
        $this->grrEntryFactory = $entryFactory;
        $this->grrEntryRepository = $entryRepository;
        $this->grrEntryManager = $entryManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $data = json_decode($this->bookingService->getData());
        } catch (\Exception $exception) {
            $io->error("Erreur obtention data: ".$exception->getMessage());
            die();
        }

        $this->bookingService->output = $output;

        echo $output->writeln("sync : ".date('Y-m-d H:i'));

        $resources = $data->resources;
        $bookings = $data->bookings;
        foreach ($bookings as $booking) {
            $this->bookingService->traitementByDates($booking);
        }

        $this->purge($output);
    }

    protected function purge(Output $output)
    {
        $entries = $this->grrEntryRepository->getBookings();

        foreach ($entries as $entry) {
            if (!in_array($entry->getBooking(), $this->bookingService->bookingsFromFlux)) {
                $this->grrEntryManager->remove($entry);
                echo $output->writeln("remove : ".$entry->getBooking());
            }
        }
        $this->grrEntryManager->flush();
    }

}
