<?php

namespace App\Entity;

use App\Booking\BookingTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;

/**
 * Entry
 *
 * @ORM\Table(name="grr_entry", indexes={@ORM\Index(name="idxEndTime", columns={"end_time"}), @ORM\Index(name="idxStartTime", columns={"start_time"})})
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 *
 * @AppAssert\BusyRoom()
 * @AppAssert\AreaTimeSlot()
 *
 */
class Entry
{
    use BookingTrait;

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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     * @Assert\LessThan(propertyPath="endTime")
     * @ORM\Column(name="start_time", type="datetime", nullable=false)
     */
    private $startTime;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @Assert\GreaterThan(propertyPath="startTime")
     * @ORM\Column(name="end_time", type="datetime", nullable=false)
     */
    private $endTime;

    /**
     * Type de periode : aucune, chaque jour, chaque semaine, chaque mois
     * @var int
     *
     * @ORM\Column(name="entry_type", type="integer", nullable=true)
     */
    private $entryType;

    /**
     * @var int
     *
     * @ORM\Column(name="repeat_id", type="integer", nullable=true)
     */
    private $repeatId;

    /**
     * @var \DateTime
     * @deprecated
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="create_by", type="string", length=100, nullable=false)
     */
    private $createBy;

    /**
     * @var string
     *
     * @ORM\Column(name="beneficiaire_ext", type="string", length=200, nullable=true)
     */
    private $beneficiaireExt;

    /**
     * @var string
     *
     * @ORM\Column(name="beneficiaire", type="string", length=100, nullable=false)
     */
    private $beneficiaire;


    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_entry", type="string", length=1, nullable=true)
     */
    private $statutEntry;

    /**
     * @var int
     *
     * @ORM\Column(name="option_reservation", type="integer", nullable=false)
     */
    private $optionReservation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="overload_desc", type="text", length=65535, nullable=true)
     */
    private $overloadDesc;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="moderate", type="boolean", nullable=true)
     */
    private $moderate;

    /**
     * @var bool
     *
     * @ORM\Column(name="jours", type="boolean", nullable=false)
     */
    private $jours;

    /**
     * @var EntryType|null
     * @ORM\ManyToOne(targetEntity="App\Entity\EntryType", inversedBy="entries")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    private $type;

    /**
     * @var Room
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * Util lors de l'ajout d'un Entry
     * @var Area|null
     */
    private $area;

    /**
     * Util lors de l'ajout d'un Entry
     * @var boolean
     */
    private $full_day = false;

    private $duration;

    private $duration2;

    /**
     * @var ArrayCollection
     */
    private $locations = [];

    /**
     * Pour l'affichage par jour, nbre de cellules occupees
     * @var int
     */
    private $cellules;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @Assert\IsTrue(message="assert.entry.startstdend")
     */
    public function isStartStEnd(): bool
    {
        return $this->startTime < $this->endTime;
    }

    /**
     * @return int
     */
    public function getCellules(): int
    {
        return $this->cellules;
    }

    /**
     * @param int $cellules
     */
    public function setCellules(int $cellules): void
    {
        $this->cellules = $cellules;
    }

    public function addLocation(array $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getDuration2()
    {
        return $this->duration2;
    }

    /**
     * @param mixed $duration2
     */
    public function setDuration2($duration2): void
    {
        $this->duration2 = $duration2;
    }


    /**
     * @return Area|null
     */
    public function getArea(): ?Area
    {
        return $this->area;
    }

    /**
     * @param Area|null $area
     */
    public function setArea(?Area $area): void
    {
        $this->area = $area;
    }

    /**
     * @return bool
     */
    public function isFullDay(): bool
    {
        return $this->full_day;
    }

    /**
     * @param bool $full_day
     */
    public function setFullDay(bool $full_day): void
    {
        $this->full_day = $full_day;
    }

    /**
     * @return Collection|array
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @param array $locations
     */
    public function setLocations(array $locations): void
    {
        $this->locations = $locations;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getEntryType(): ?int
    {
        return $this->entryType;
    }

    public function setEntryType(?int $entryType): self
    {
        $this->entryType = $entryType;

        return $this;
    }

    public function getRepeatId(): ?int
    {
        return $this->repeatId;
    }

    public function setRepeatId(?int $repeatId): self
    {
        $this->repeatId = $repeatId;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function setCreateBy(string $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getBeneficiaireExt(): ?string
    {
        return $this->beneficiaireExt;
    }

    public function setBeneficiaireExt(?string $beneficiaireExt): self
    {
        $this->beneficiaireExt = $beneficiaireExt;

        return $this;
    }

    public function getBeneficiaire(): ?string
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(string $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatutEntry(): ?string
    {
        return $this->statutEntry;
    }

    public function setStatutEntry(?string $statutEntry): self
    {
        $this->statutEntry = $statutEntry;

        return $this;
    }

    public function getOptionReservation(): ?int
    {
        return $this->optionReservation;
    }

    public function setOptionReservation(int $optionReservation): self
    {
        $this->optionReservation = $optionReservation;

        return $this;
    }

    public function getOverloadDesc(): ?string
    {
        return $this->overloadDesc;
    }

    public function setOverloadDesc(?string $overloadDesc): self
    {
        $this->overloadDesc = $overloadDesc;

        return $this;
    }

    public function getModerate(): ?bool
    {
        return $this->moderate;
    }

    public function setModerate(?bool $moderate): self
    {
        $this->moderate = $moderate;

        return $this;
    }

    public function getJours(): ?bool
    {
        return $this->jours;
    }

    public function setJours(bool $jours): self
    {
        $this->jours = $jours;

        return $this;
    }

    public function getType(): ?EntryType
    {
        return $this->type;
    }

    public function setType(?EntryType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }


}
