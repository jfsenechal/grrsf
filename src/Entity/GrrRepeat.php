<?php

namespace App\Entity;

use App\Booking\BookingTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * GrrRepeat
 *
 * @ORM\Table(name="grr_repeat")
 * @ORM\Entity(repositoryClass="App\Repository\GrrRepeatRepository")
 */
class GrrRepeat
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
     * @var int
     *
     * @ORM\Column(name="start_time", type="integer", nullable=false)
     */
    private $startTime = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="end_time", type="integer", nullable=false)
     */
    private $endTime = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="rep_type", type="integer", nullable=false)
     */
    private $repType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="end_date", type="integer", nullable=false)
     */
    private $endDate = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="rep_opt", type="string", length=32, nullable=false)
     */
    private $repOpt = '';

    /**
     * @var int
     *
     * @ORM\Column(name="room_id", type="integer", nullable=false, options={"default"="1"})
     */
    private $roomId = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="create_by", type="string", length=100, nullable=false)
     */
    private $createBy = '';

    /**
     * @var string
     *
     * @ORM\Column(name="beneficiaire_ext", type="string", length=200, nullable=false)
     */
    private $beneficiaireExt;

    /**
     * @var string
     *
     * @ORM\Column(name="beneficiaire", type="string", length=100, nullable=false)
     */
    private $beneficiaire = '';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="rep_num_weeks", type="boolean", nullable=true)
     */
    private $repNumWeeks = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="overload_desc", type="text", length=65535, nullable=true)
     */
    private $overloadDesc;

    /**
     * @var bool
     *
     * @ORM\Column(name="jours", type="boolean", nullable=false)
     */
    private $jours = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    public function setStartTime(int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getRepType(): ?int
    {
        return $this->repType;
    }

    public function setRepType(int $repType): self
    {
        $this->repType = $repType;

        return $this;
    }

    public function getEndDate(): ?int
    {
        return $this->endDate;
    }

    public function setEndDate(int $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRepOpt(): ?string
    {
        return $this->repOpt;
    }

    public function setRepOpt(string $repOpt): self
    {
        $this->repOpt = $repOpt;

        return $this;
    }

    public function getRoomId(): ?int
    {
        return $this->roomId;
    }

    public function setRoomId(int $roomId): self
    {
        $this->roomId = $roomId;

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

    public function setBeneficiaireExt(string $beneficiaireExt): self
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getRepNumWeeks(): ?bool
    {
        return $this->repNumWeeks;
    }

    public function setRepNumWeeks(?bool $repNumWeeks): self
    {
        $this->repNumWeeks = $repNumWeeks;

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

    public function getJours(): ?bool
    {
        return $this->jours;
    }

    public function setJours(bool $jours): self
    {
        $this->jours = $jours;

        return $this;
    }


}
