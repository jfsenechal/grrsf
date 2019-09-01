<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use App\Model\DurationModel;
use App\Model\TimeSlot;
use App\Validator as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entry.
 *
 * @ORM\Table(name="grr_entry")
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 *
 * @AppAssert\BusyRoom()
 * @AppAssert\AreaTimeSlot()
 */
class Entry
{
    use IdEntityTrait;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     * @Assert\LessThan(propertyPath="end_time")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $start_time;

    /**
     * @var \DateTimeInterface
     * @Assert\DateTime()
     * @Assert\GreaterThan(propertyPath="start_time")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $end_time;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $created_by;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $beneficiaireExt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $beneficiaire;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $statut_entry;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $option_reservation;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $overloadDesc;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $moderate;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $private;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
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
     * Util lors de l'ajout d'un Entry.
     *
     * @var Area|null
     */
    private $area;

    /**
     * @var DurationModel
     */
    private $duration;

    /**
     * Pour l'affichage, TimeSlot présents
     * @var ArrayCollection|TimeSlot[]
     */
    private $locations = [];

    /**
     * Pour l'affichage par jour, nbre de cellules occupees.
     *
     * @var int
     */
    private $cellules;

    /**
     * @Assert\Type(type="App\Entity\Periodicity")
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity="App\Entity\Periodicity", inversedBy="entry", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $periodicity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PeriodicityDay", mappedBy="entry", cascade={"remove"}, orphanRemoval=true)
     */
    private $periodicity_days;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->periodicity_days = new ArrayCollection();
        $this->private = false;
        $this->moderate = false;
        $this->jours = false;
        $this->created_by = 'jf';
        $this->beneficiaire = 'jf';
        $this->option_reservation = 0;
    }

    public function __toString()
    {
        return $this->name;
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
     * @return Entry
     */
    public function setCellules(int $cellules): self
    {
        $this->cellules = $cellules;

        return $this;
    }

    public function addLocation(array $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
        }

        return $this;
    }

    /**
     * @return DurationModel|null
     */
    public function getDuration(): ?DurationModel
    {
        return $this->duration;
    }

    /**
     * @param DurationModel|null $duration
     * @return Entry
     */
    public function setDuration(?DurationModel $duration): self
    {
        $this->duration = $duration;

        return $this;
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
     * @return Collection|array|TimeSlot[]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    public function setCreatedBy(string $created_by): self
    {
        $this->created_by = $created_by;

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
        return $this->statut_entry;
    }

    public function setStatutEntry(?string $statut_entry): self
    {
        $this->statut_entry = $statut_entry;

        return $this;
    }

    public function getOptionReservation(): ?int
    {
        return $this->option_reservation;
    }

    public function setOptionReservation(int $option_reservation): self
    {
        $this->option_reservation = $option_reservation;

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

    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

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

    public function getPeriodicity(): ?Periodicity
    {
        return $this->periodicity;
    }

    public function setPeriodicity(?Periodicity $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    /**
     * @return Collection|PeriodicityDay[]
     */
    public function getPeriodicityDays(): Collection
    {
        return $this->periodicity_days;
    }

    public function addPeriodicityDay(PeriodicityDay $periodicityDay): self
    {
        if (!$this->periodicity_days->contains($periodicityDay)) {
            $this->periodicity_days[] = $periodicityDay;
            $periodicityDay->setEntry($this);
        }

        return $this;
    }

    public function removePeriodicityDay(PeriodicityDay $periodicityDay): self
    {
        if ($this->periodicity_days->contains($periodicityDay)) {
            $this->periodicity_days->removeElement($periodicityDay);
            // set the owning side to null (unless already changed)
            if ($periodicityDay->getEntry() === $this) {
                $periodicityDay->setEntry(null);
            }
        }

        return $this;
    }

}
