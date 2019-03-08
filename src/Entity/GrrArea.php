<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrArea
 *
 * @ORM\Table(name="grr_area")
 * @ORM\Entity(repositoryClass="GrrAreaRepository")
 */
class GrrArea
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="area_name", type="string", length=30, nullable=false)
     */
    private $areaName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="access", type="boolean", nullable=false)
     */
    private $access = false;

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
     */
    private $orderDisplay = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ip_adr", type="string", length=15, nullable=false)
     */
    private $ipAdr = '';

    /**
     * @var int
     *
     * @ORM\Column(name="morningstarts_area", type="smallint", nullable=false)
     */
    private $morningstartsArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="eveningends_area", type="smallint", nullable=false)
     */
    private $eveningendsArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="resolution_area", type="integer", nullable=false)
     */
    private $resolutionArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="eveningends_minutes_area", type="smallint", nullable=false)
     */
    private $eveningendsMinutesArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="weekstarts_area", type="smallint", nullable=false)
     */
    private $weekstartsArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="twentyfourhour_format_area", type="smallint", nullable=false)
     */
    private $twentyfourhourFormatArea = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="calendar_default_values", type="string", length=1, nullable=false, options={"default"="y","fixed"=true})
     */
    private $calendarDefaultValues = 'y';

    /**
     * @var string
     *
     * @ORM\Column(name="enable_periods", type="string", length=1, nullable=false, options={"default"="n"})
     */
    private $enablePeriods = 'n';

    /**
     * @var string
     *
     * @ORM\Column(name="display_days", type="array", nullable=false)
     */
    private $displayDays;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type_par_defaut", type="integer", nullable=false, options={"default"="-1"})
     */
    private $idTypeParDefaut = '-1';

    /**
     * @var int
     *
     * @ORM\Column(name="duree_max_resa_area", type="integer", nullable=false, options={"default"="-1"})
     */
    private $dureeMaxResaArea = '-1';

    /**
     * @var int
     *
     * @ORM\Column(name="duree_par_defaut_reservation_area", type="integer", nullable=false)
     */
    private $dureeParDefautReservationArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="max_booking", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $maxBooking = '-1';

    public function __toString()
    {
        return $this->areaName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAreaName(): ?string
    {
        return $this->areaName;
    }

    public function setAreaName(string $areaName): self
    {
        $this->areaName = $areaName;

        return $this;
    }

    public function getOrderDisplay(): ?int
    {
        return $this->orderDisplay;
    }

    public function setOrderDisplay(int $orderDisplay): self
    {
        $this->orderDisplay = $orderDisplay;

        return $this;
    }

    public function getIpAdr(): ?string
    {
        return $this->ipAdr;
    }

    public function setIpAdr(string $ipAdr): self
    {
        $this->ipAdr = $ipAdr;

        return $this;
    }

    public function getMorningstartsArea(): ?int
    {
        return $this->morningstartsArea;
    }

    public function setMorningstartsArea(int $morningstartsArea): self
    {
        $this->morningstartsArea = $morningstartsArea;

        return $this;
    }

    public function getEveningendsArea(): ?int
    {
        return $this->eveningendsArea;
    }

    public function setEveningendsArea(int $eveningendsArea): self
    {
        $this->eveningendsArea = $eveningendsArea;

        return $this;
    }

    public function getResolutionArea(): ?int
    {
        return $this->resolutionArea;
    }

    public function setResolutionArea(int $resolutionArea): self
    {
        $this->resolutionArea = $resolutionArea;

        return $this;
    }

    public function getEveningendsMinutesArea(): ?int
    {
        return $this->eveningendsMinutesArea;
    }

    public function setEveningendsMinutesArea(int $eveningendsMinutesArea): self
    {
        $this->eveningendsMinutesArea = $eveningendsMinutesArea;

        return $this;
    }

    public function getWeekstartsArea(): ?int
    {
        return $this->weekstartsArea;
    }

    public function setWeekstartsArea(int $weekstartsArea): self
    {
        $this->weekstartsArea = $weekstartsArea;

        return $this;
    }

    public function getTwentyfourhourFormatArea(): ?int
    {
        return $this->twentyfourhourFormatArea;
    }

    public function setTwentyfourhourFormatArea(int $twentyfourhourFormatArea): self
    {
        $this->twentyfourhourFormatArea = $twentyfourhourFormatArea;

        return $this;
    }

    public function getCalendarDefaultValues(): ?string
    {
        return $this->calendarDefaultValues;
    }

    public function setCalendarDefaultValues(string $calendarDefaultValues): self
    {
        $this->calendarDefaultValues = $calendarDefaultValues;

        return $this;
    }

    public function getEnablePeriods(): ?string
    {
        return $this->enablePeriods;
    }

    public function setEnablePeriods(string $enablePeriods): self
    {
        $this->enablePeriods = $enablePeriods;

        return $this;
    }

    public function getIdTypeParDefaut(): ?int
    {
        return $this->idTypeParDefaut;
    }

    public function setIdTypeParDefaut(int $idTypeParDefaut): self
    {
        $this->idTypeParDefaut = $idTypeParDefaut;

        return $this;
    }

    public function getDureeMaxResaArea(): ?int
    {
        return $this->dureeMaxResaArea;
    }

    public function setDureeMaxResaArea(int $dureeMaxResaArea): self
    {
        $this->dureeMaxResaArea = $dureeMaxResaArea;

        return $this;
    }

    public function getDureeParDefautReservationArea(): ?int
    {
        return $this->dureeParDefautReservationArea;
    }

    public function setDureeParDefautReservationArea(int $dureeParDefautReservationArea): self
    {
        $this->dureeParDefautReservationArea = $dureeParDefautReservationArea;

        return $this;
    }

    public function getMaxBooking(): ?int
    {
        return $this->maxBooking;
    }

    public function setMaxBooking(int $maxBooking): self
    {
        $this->maxBooking = $maxBooking;

        return $this;
    }

    public function getAccess(): ?bool
    {
        return $this->access;
    }

    public function setAccess(bool $access): self
    {
        $this->access = $access;

        return $this;
    }

    public function getDisplayDays(): ?array
    {
        return $this->displayDays;
    }

    public function setDisplayDays(array $displayDays): self
    {
        $this->displayDays = $displayDays;

        return $this;
    }


}
