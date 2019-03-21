<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 13:35
 */

namespace App\Booking;

use App\Entity\Entry;
use App\Factory\EntryFactory;
use App\Manager\EntryManager;
use App\Repository\EntryRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BookingService
{

    protected $sallesGrr = [
        113 => 'Box',
        114 => 'Créative',
        115 => 'Meeting Room',
        116 => 'Relax Room',
        117 => 'Digital Room',
    ];

    /**
     * 1 => 'Box',
     * 2 => 'LA CREATIVE',
     * 3 => 'DIGITAL ROOM',
     * 4 => 'RELAX ROOM',
     * 5 => 'MEETING',
     * @var array
     */
    protected $sallesGrrProd = [
        1 => 113,
        2 => 114,
        3 => 117,
        4 => 116,
        5 => 115,
    ];

    /**
     * @var array
     * LA BOX
     * LA CREATIVE
     * DIGITAL ROOM
     * RELAX ROOM
     * La MEETING
     */
    protected $salles = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
    ];

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var EntryRepository
     */
    private $grrEntryRepository;
    /**
     * @var EntryFactory
     */
    private $grrEntryFactory;
    /**
     * @var EntryManager
     */
    private $grrEntryManager;
    /**
     * @var OutputInterface
     */
    public $output;

    /**
     * @var array
     */
    public $bookingsFromFlux;

    public function __construct(
        ParameterBagInterface $parameterBag,
        EntryRepository $grrEntryRepository,
        EntryFactory $grrEntryFactory,
        EntryManager $grrEntryManager
    ) {
        $this->parameterBag = $parameterBag;
        $this->grrEntryRepository = $grrEntryRepository;
        $this->grrEntryFactory = $grrEntryFactory;
        $this->grrEntryManager = $grrEntryManager;
        $this->bookingsFromFlux = [];
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

    public function traitementByDates($booking)
    {
        if (count($booking->dates) === 2) {
            $this->traitement2Dates($booking);
        } else {
            foreach ($booking->dates as $date) {
                $firstDate = $date->booking_date;

                $grrEntry = $this->createGrrEntry($booking, $firstDate);
                $dateTimeDebut = $this->convertToDateTime($firstDate);
                $dateTimeFin = $this->convertToDateTime($firstDate);

                $this->output->write($firstDate.' ==> ');

                $this->setHeureDebut($dateTimeDebut);
                $this->setHeureFin($dateTimeFin, true);

                $this->output->write($dateTimeDebut->format('Y-m-d H:i:s').' , ');
                $this->output->writeln($dateTimeFin->format('Y-m-d H:i:s'));

                $grrEntry->setStartTime($dateTimeDebut->getTimestamp());
                $grrEntry->setEndTime($dateTimeFin->getTimestamp());
                $this->grrEntryManager->insert($grrEntry);
            }
        }
    }

    private function traitement2Dates($booking)
    {
        $firstDate = $booking->dates[0]->booking_date;
        $secondDate = $booking->dates[1]->booking_date;
        $grrEntry = $this->createGrrEntry($booking, $firstDate);
        $dateTimeDebut = $this->convertToDateTime($firstDate);
        $dateTimeFin = $this->convertToDateTime($secondDate);

        $this->output->write($firstDate.'|'.$secondDate.' ==> ');

        $this->setHeureDebut($dateTimeDebut);
        $this->setHeureFin($dateTimeFin);

        $this->output->write($dateTimeDebut->format('Y-m-d H:i:s').' , ');
        $this->output->writeln($dateTimeFin->format('Y-m-d H:i:s'));

        $grrEntry->setStartTime($dateTimeDebut->getTimestamp());
        $grrEntry->setEndTime($dateTimeFin->getTimestamp());

        $this->grrEntryManager->insert($grrEntry);
    }

    private function setHeureDebut(\DateTime $dateTime)
    {
        $heure = $dateTime->format('H:i');
        if ($heure === '00:00') {
            $dateTime->setTime(8, 00);
        }
    }

    private function setHeureFin(\DateTime $dateTime, bool $force = false)
    {
        $heure = $dateTime->format('H:i');

        if ($force == true) {
            $dateTime->setTime(23, 00);
        } elseif ($heure === '00:00') {
            $dateTime->setTime(23, 00);
        }
    }


    /**
     * @param $booking
     * @return Entry
     */
    public function createGrrEntry($booking, string $date): Entry
    {
        $form_data = $booking->form_data;
        //13:00 - 18:00
        $rangetime = $form_data->rangetime;
        $fields = $form_data->_all_fields_;
        $grrEntry = $this->getInstance($booking, $date);
        $grrEntry->setName($this->removeAccents($fields->name).' '.$this->removeAccents($fields->secondname));
        $grrEntry->setTimestamp($this->convertToDateTime($booking->modification_date));
        $grrEntry->setRoomId($this->getCorrespondanceRoom($booking->booking_type));
        $grrEntry->setDescription($this->getDescriptionString($fields));
        $grrEntry->setBeneficiaire('esquare');
        $grrEntry->setCreateBy('esquare');
        $this->setDefaultFields($grrEntry);

        return $grrEntry;
    }

    public function getInstance($booking, string $date): Entry
    {
        $key = $this->getKeyBooking($booking, $date);
        $this->bookingsFromFlux[] = $key;
        $grrEntry = $this->grrEntryRepository->findOneBy(['booking' => $key]);
        if (!$grrEntry) {
            echo $this->output->writeln("nouveau : $date => $key");
            $grrEntry = $this->grrEntryFactory->createNew();
            $grrEntry->setBooking($key);
        }

        return $grrEntry;
    }

    protected function getKeyBooking($booking, $date)
    {
        return $booking->booking_id.'_'.$this->convertToDateTime($date)->format('Y-m-d');
    }

    private function setDefaultFields(Entry $grrEntry)
    {
        $grrEntry->setBeneficiaireExt(' ');//pas null
        $grrEntry->setType('B');
        $grrEntry->setModerate(0);
        $grrEntry->setJours(0);
        $grrEntry->setEntryType(0);
        $grrEntry->setOptionReservation(-1);
        $grrEntry->setStatutEntry('-');
    }

    public function convertToDateTime(string $date): \DateTime
    {
        try {
            return \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            die();
        }
    }

    public function getDescriptionString($fields)
    {
        $txt = '';
        $txt .= $this->removeAccents($fields->name.' ');
        $txt .= $this->removeAccents($fields->secondname.' ');
        $txt .= $this->removeAccents($fields->phone.' ');
        $txt .= $this->removeAccents($fields->email.' ');
        $txt .= $this->removeAccents($fields->details.' ');

        return $txt;
    }

    public function getCorrespondanceRoom($room)
    {
        return $this->sallesGrrProd[$room];
        //return $this->salles[$room];
    }

    public function removeAccents(string $string)
    {
        $unwanted_array = array(
            'Š' => 'S',
            'š' => 's',
            'Ž' => 'Z',
            'ž' => 'z',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'Ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y',
        );

        return strtr($string, $unwanted_array);
    }
}