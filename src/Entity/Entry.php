<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\NameEntityTrait;
use App\Doctrine\Traits\TimestampableEntityTrait;
use App\Model\DurationModel;
use App\Model\TimeSlot;
use App\Validator\Entry as AppAssertEntry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entry.
 *
 * @ORM\Table(name="entry")
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 * @AppAssertEntry\BusyRoom
 * @AppAssertEntry\AreaTimeSlot
 * @ApiResource
 */
class Entry
{
    use IdEntityTrait;
    use NameEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime
     * @Assert\LessThan(propertyPath="endTime", message="entry.constraint.start_smaller_end")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startTime;

    /**
     * @var \DateTimeInterface
     * @Assert\DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $createdBy;

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
    private $statutEntry;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $optionReservation;

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
     * @ORM\Column(type="boolean", options={"default": 0})
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
     * Pour l'affichage, TimeSlot présents.
     *
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Periodicity", inversedBy="entries", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $periodicity;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->locations = new ArrayCollection();
        $this->private = false;
        $this->moderate = false;
        $this->jours = false;
        $this->beneficiaire = 'jf';
        $this->optionReservation = 0;
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
     *
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
     *
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

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

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
}
