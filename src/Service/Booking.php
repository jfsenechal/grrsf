<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 13:35
 */

namespace App\Service;


use App\Entity\GrrEntry;
use App\Repository\GrrEntryRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Booking
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
     * @var GrrEntryRepository
     */
    private $grrEntryRepository;

    public function __construct(ParameterBagInterface $parameterBag, GrrEntryRepository $grrEntryRepository)
    {
        $this->parameterBag = $parameterBag;
        $this->grrEntryRepository = $grrEntryRepository;
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
        $form_data = $booking->form_data;
        //13:00 - 18:00
        $rangetime = $form_data->rangetime;
        $fields = $form_data->_all_fields_;
        $this->createGrrEntry($booking, $fields, $booking->dates);

    }

    /**
     * @param $booking
     * @param $fields
     * @param $date 2019-03-19 18:00:02
     */
    public function createGrrEntry($booking, $fields, $dates)
    {
        $grrEntry = $this->getInstance($booking->booking_id);
        $grrEntry->setName($this->removeAccents($fields->name).' '.$this->removeAccents($fields->secondname));
        $grrEntry->setTimestamp($this->getDate($booking->modification_date));
        $grrEntry->setBooking($booking->booking_id);
        $grrEntry->setRoomId($this->getCorrespondanceRoom($booking->booking_type));
        $grrEntry->setDescription($this->getDescriptionString($fields));
        $grrEntry->setBeneficiaire('esquare');
        $grrEntry->setCreateBy('esquare');
        $this->setDefaultFields($grrEntry);
        $this->traitementDates($grrEntry, $dates);
    }

    public function getInstance(int $bookingId): GrrEntry
    {
        $grrEntry = $this->grrEntryRepository->findOneBy(['booking' => $bookingId]);
        if (!$grrEntry) {
            $grrEntry = new GrrEntry();
        }

        return $grrEntry;
    }

    protected function traitementDates(GrrEntry $grrEntry, $dates)
    {
        if (count($dates) == 2) {
            $this->traitement2Dates($grrEntry, $dates);

            return;
        }

        foreach ($dates as $date) {
            $grrEntryClone = clone($grrEntry);
            $dateTimeDebut = $this->getDate($date->booking_date);
            $dateTimeFin = clone($dateTimeDebut);
            $heure = $dateTimeDebut->format('H:i:s');
            if ($heure === '00:00:00') {
                //toute la journee de 8h a 23h
                $dateTimeDebut->setTime(8, 00);
                $dateTimeFin->setTime(23, 00);
            } else {
                $dateTimeFin->setTime(23, 00);
            }
            $grrEntryClone->setStartTime($dateTimeDebut->getTimestamp());
            $grrEntryClone->setEndTime($dateTimeFin->getTimestamp());
            $this->grrEntryRepository->insert($grrEntryClone);
        }
    }

    private function traitement2Dates(GrrEntry $grrEntry, $dates)
    {
        $dateTimeDebut = $this->getDate($dates[0]->booking_date);
        $dateTimeFin = $this->getDate($dates[1]->booking_date);

        $grrEntry->setStartTime($dateTimeDebut->getTimestamp());
        $grrEntry->setEndTime($dateTimeFin->getTimestamp());

        $this->grrEntryRepository->insert($grrEntry);
    }

    private function setDefaultFields(GrrEntry $grrEntry)
    {
        $grrEntry->setBeneficiaireExt(' ');//pas null
        $grrEntry->setType('B');
        $grrEntry->setModerate(0);
        $grrEntry->setJours(0);
        $grrEntry->setEntryType(0);
        $grrEntry->setOptionReservation(-1);
        $grrEntry->setStatutEntry('-');
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
        $txt .= $this->removeAccents($fields->name.' ');
        $txt .= $this->removeAccents($fields->secondname.' ');
        $txt .= $this->removeAccents($fields->phone.' ');
        $txt .= $this->removeAccents($fields->email.' ');
        $txt .= $this->removeAccents($fields->details.' ');

        return $txt;
    }

    public function getCorrespondanceRoom($room)
    {
        return $this->salles[$room];
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