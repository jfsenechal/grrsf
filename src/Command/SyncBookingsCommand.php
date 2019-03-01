<?php

namespace App\Command;

use App\Booking\Booking;
use App\Factory\GrrEntryFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncBookingsCommand extends Command
{
    protected static $defaultName = 'SyncBookings';

    /**
     * @var Booking
     */
    private $booking;
    /**
     * @var GrrEntryFactory
     */
    private $grrEntryFactory;

    public function __construct(?string $name = null, Booking $booking, GrrEntryFactory $grrEntryFactory)
    {
        parent::__construct($name);
        $this->booking = $booking;
        $this->grrEntryFactory = $grrEntryFactory;
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
            //print_r($this->booking->getData());
            $data = json_decode($this->booking->getData());
        } catch (\Exception $exception) {
            $io->error("Erreur obtention data: ".$exception->getMessage());
            die();
        }

        $t = $this->grrEntryFactory->createNew();
        var_dump($t);

        $resources = $data->resources;
        $bookings = $data->bookings;
        foreach ($bookings as $booking) {
            //     $this->booking->traitementBooking($booking);
        }

        //  $io->success('ok');
    }


}
