<?php

namespace App\Entity\Old;

/**
 * EntryModerate.
 *
 * ORM\Table(name="grr_entry_moderate", indexes={ORM\Index(name="idxEndTime", columns={"end_time"}), ORM\Index(name="idxStartTime", columns={"start_time"})})
 * ORM\Entity
 */
class EntryModerate
{
    /**
     * @var int
     *
     * ORM\Column( type="integer", nullable=false)
     * ORM\Id
     * ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * ORM\Column(name="login_moderateur", type="string", length=40, nullable=false)
     */
    private $loginModerateur = '';

    /**
     * @var string
     *
     * ORM\Column(name="motivation_moderation", type="text", length=65535, nullable=false)
     */
    private $motivationModeration;

    /**
     * @var int
     *
     * ORM\Column(name="start_time", type="integer", nullable=false)
     */
    private $startTime = '0';

    /**
     * @var int
     *
     * ORM\Column(name="end_time", type="integer", nullable=false)
     */
    private $endTime = '0';

    /**
     * @var int
     *
     * ORM\Column(name="entry_type", type="integer", nullable=false)
     */
    private $entryType = '0';

    /**
     * @var int
     *
     * ORM\Column(name="repeat_id", type="integer", nullable=false)
     */
    private $repeatId = '0';

    /**
     * @var int
     *
     * ORM\Column(name="room_id", type="integer", nullable=false, options={"default"="1"})
     */
    private $roomId = '1';

    /**
     * @var \DateTime
     *
     * ORM\Column(name="timestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * ORM\Column(name="create_by", type="string", length=100, nullable=false)
     */
    private $createBy = '';

    /**
     * @var string
     *
     * ORM\Column(name="beneficiaire_ext", type="string", length=200, nullable=false)
     */
    private $beneficiaireExt;

    /**
     * @var string
     *
     * ORM\Column(name="beneficiaire", type="string", length=100, nullable=false)
     */
    private $beneficiaire = '';

    /**
     * @var string
     *
     * ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name = '';

    /**
     * @var string|null
     *
     * ORM\Column(name="type", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $type;

    /**
     * @var string|null
     *
     * ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * ORM\Column(name="statut_entry", type="string", length=1, nullable=false, options={"default"="-","fixed"=true})
     */
    private $statutEntry = '-';

    /**
     * @var int
     *
     * ORM\Column(name="option_reservation", type="integer", nullable=false)
     */
    private $optionReservation = '0';

    /**
     * @var string|null
     *
     * ORM\Column(name="overload_desc", type="text", length=65535, nullable=true)
     */
    private $overloadDesc;

    /**
     * @var bool|null
     *
     * ORM\Column(name="moderate", type="boolean", nullable=true)
     */
    private $moderate = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoginModerateur(): ?string
    {
        return $this->loginModerateur;
    }

    public function setLoginModerateur(string $loginModerateur): self
    {
        $this->loginModerateur = $loginModerateur;

        return $this;
    }

    public function getMotivationModeration(): ?string
    {
        return $this->motivationModeration;
    }

    public function setMotivationModeration(string $motivationModeration): self
    {
        $this->motivationModeration = $motivationModeration;

        return $this;
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

    public function getEntryType(): ?int
    {
        return $this->entryType;
    }

    public function setEntryType(int $entryType): self
    {
        $this->entryType = $entryType;

        return $this;
    }

    public function getRepeatId(): ?int
    {
        return $this->repeatId;
    }

    public function setRepeatId(int $repeatId): self
    {
        $this->repeatId = $repeatId;

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

    public function getStatutEntry(): ?string
    {
        return $this->statutEntry;
    }

    public function setStatutEntry(string $statutEntry): self
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
}
