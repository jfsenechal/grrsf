<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Area
 *
 * @ORM\Table(name="grr_area")
 * @ORM\Entity(repositoryClass="App\Repository\AreaRepository")
 */
class Area
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
    private $areaName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="access",type="boolean", nullable=false)
     */
    private $access = false;

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
     */
    private $orderDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_adr", type="string", length=15, nullable=true)
     */
    private $ipAdr;

    /**
     * @var int
     *
     * @ORM\Column(name="morningstarts_area", type="smallint", nullable=false)
     */
    private $morningstartsArea;

    /**
     * @var int
     *
     * @ORM\Column(name="eveningends_area", type="smallint", nullable=false)
     */
    private $eveningendsArea;

    /**
     * @var int
     *
     * @ORM\Column(name="resolution_area", type="integer", nullable=false)
     */
    private $resolutionArea;

    /**
     * @var int
     *
     * @ORM\Column(name="eveningends_minutes_area", type="smallint", nullable=false)
     */
    private $eveningendsMinutesArea;

    /**
     * @var int
     *
     * @ORM\Column(name="weekstarts_area", type="smallint", nullable=false)
     */
    private $weekstartsArea;

    /**
     * @var int
     *
     * @ORM\Column(name="twentyfourhour_format_area", type="smallint", nullable=false)
     */
    private $twentyfourhourFormatArea;

    /**
     * @var boolean
     *
     * @ORM\Column(name="calendar_default_values", type="boolean", nullable=false, options={"default"="1"})
     */
    private $calendarDefaultValues;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enable_periods", type="boolean", nullable=false, options={"default"="0"})
     */
    private $enablePeriods;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $displayDays;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type_par_defaut", type="integer", nullable=false, options={"default"="-1"})
     */
    private $idTypeParDefaut;

    /**
     * @var int
     *
     * @ORM\Column(name="duree_max_resa_area", type="integer", nullable=false, options={"default"="-1"})
     */
    private $dureeMaxResaArea;

    /**
     * @var int
     *
     * @ORM\Column(name="duree_par_defaut_reservation_area", type="integer", nullable=false)
     */
    private $dureeParDefautReservationArea;

    /**
     * @var int
     *
     * @ORM\Column(name="max_booking", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $maxBooking ;

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

    public function getAccess(): ?bool
    {
        return $this->access;
    }

    public function setAccess(bool $access): self
    {
        $this->access = $access;

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

    public function setIpAdr(?string $ipAdr): self
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

    public function getCalendarDefaultValues(): ?bool
    {
        return $this->calendarDefaultValues;
    }

    public function setCalendarDefaultValues(bool $calendarDefaultValues): self
    {
        $this->calendarDefaultValues = $calendarDefaultValues;

        return $this;
    }

    public function getEnablePeriods(): ?bool
    {
        return $this->enablePeriods;
    }

    public function setEnablePeriods(bool $enablePeriods): self
    {
        $this->enablePeriods = $enablePeriods;

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
}
