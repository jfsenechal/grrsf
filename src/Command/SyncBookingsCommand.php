<?php

namespace App\Command;

use App\Entity\GrrEntry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SyncBookingsCommand extends Command
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    protected static $defaultName = 'SyncBookings';

    protected $sallesGrr = [
        113 => 'Box',
        117 => 'Digital Room',
        115 => 'Meeting Room',
        116 => 'Relax Room',
        114 => 'Créative',
    ];

    /**
     * 1 => 'Box',
     * 2 => 'LA CREATIVE',
     * 3 => 'DIGITAL ROOM',
     * 4 => 'RELAX ROOM',
     * 5 => 'MEETING',
     * @var array
     */
    protected $salles = [
        1 => 113,
        2 => 114,
        3 => 117,
        4 => 116,
        5 => 115,
    ];

    public function __construct(?string $name = null, ParameterBagInterface $parameterBag)
    {
        parent::__construct($name);
        $this->parameterBag = $parameterBag;
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
            $data = json_decode($this->getData());
        } catch (\Exception $exception) {
            $io->error("Erreur obtention data: ".$exception->getMessage());
            die();
        }

        $resources = $data['resources'];
        $bookings = $data['bookings'];
        foreach ($bookings as $booking) {
            $this->traitementBooking($booking);
        }

        $io->success('ok');
    }

    public function getData()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->parameterBag->get('booking_url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'X-Authorization: '.$this->parameterBag->get('booking_token'),
            )
        );
        $server_output = curl_exec($ch);
        curl_close($ch);

        return $server_output;
    }

    public function traitementBooking($booking)
    {
        $booking_id = $booking->booking_id;
        $form_data = $booking->form_data;
        $fields = $form_data['_all_fields_'];
        foreach ($booking->dates as $date) {
            if (!$date->approved) {

            }
            $date->booking_date;
        }

    }

    /**
     * @param $booking
     * @param $fields
     * @param $date 2019-03-19 18:00:02
     */
    public function createGrrEntry($booking, $fields, $date)
    {
        $grrEntry = new GrrEntry();
        $grrEntry->setStartTime($this->getDate($date->booking_date))->getTimestamp();
        $grrEntry->setEndTime($this->getDate($date->booking_date))->getTimestamp();
        $grrEntry->setTimestamp($this->getDate($booking->modification_date));
        $grrEntry->setBooking($booking->booking_id);
        $grrEntry->setRoomId($this->getCorrespondanceRoom($booking->booking_type));
        $grrEntry->setDescription($this->getDescriptionString($fields));
        $grrEntry->setBeneficiaire();
        $grrEntry->setName();
        $grrEntry->setCreateBy();
        $grrEntry->setType();
        $grrEntry->setModerate();
        $grrEntry->setJours();
        $grrEntry->setEntryType();
        $grrEntry->setOptionReservation();
        $grrEntry->setStatutEntry();
    }

    public function getDate(string $date)
    {
        try {
           return \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function getDescriptionString($fields)
    {
        $txt = '';
        $txt .= $fields->name.'<br />';
        $txt .= $fields->secondname.'<br />';
        $txt .= $fields->phone.'<br />';
        $txt .= $fields->email.'<br />';

        return $txt;
    }

    public function getCorrespondanceRoom($room)
    {
        return $this->salles[$room];
    }

    /**
     * Resource
     * booking_type_id    :    1
     * title    :    LA BOX
     * import    :    null
     * export    :    null
     * id    :    1
     * count    :    1
     * ID    :    1
     */

}
