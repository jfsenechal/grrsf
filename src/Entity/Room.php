<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\NameEntityTrait;
use App\Entity\Security\Authorization;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Room.
 *
 * @ORM\Table(name="room")
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ApiResource
 */
class Room
{
    use IdEntityTrait;
    use NameEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $capacity;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $maximumBooking;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $statutRoom;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_fic_room", type="boolean", nullable=false)
     */
    private $showFicRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_room", type="string", length=50, nullable=true)
     */
    private $pictureRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_room", type="text", length=65535, nullable=true)
     */
    private $commentRoom;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_comment", type="boolean", nullable=false)
     */
    private $showComment;

    /**
     * @var int
     *
     * @ORM\Column(name="delais_max_resa_room", type="smallint", nullable=false)
     */
    private $delaisMaxResaRoom;

    /**
     * @var int
     *
     * @ORM\Column(name="delais_min_resa_room", type="smallint", nullable=false)
     */
    private $delaisMinResaRoom;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $allowActionInPast;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $orderDisplay;

    /**
     * @var int
     *
     * @ORM\Column(name="delais_option_reservation", type="smallint", nullable=false)
     */
    private $delaisOptionReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="dont_allow_modify", type="boolean", nullable=false)
     */
    private $dontAllowModify;

    /**
     * @var int
     *
     * @ORM\Column(name="type_affichage_reser", type="smallint", nullable=false)
     */
    private $typeAffichageReser;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="moderate", type="boolean", nullable=true)
     */
    private $moderate;

    /**
     * @var string
     *
     * @ORM\Column(name="qui_peut_reserver_pour", type="string", length=1, nullable=false)
     */
    private $quiPeutReserverPour;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $activeRessourceEmpruntee;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $ruleToAdd;

    /**
     * @var Area
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $area;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="room", cascade={"remove"})
     *
     * @var \App\Entity\Entry[]|\Doctrine\Common\Collections\Collection
     */
    private $entries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\Authorization", mappedBy="room", orphanRemoval=true)
     *
     * @var \App\Entity\Security\Authorization[]|\Doctrine\Common\Collections\Collection
     */
    private $authorizations;

    public function __construct(Area $area)
    {
        $this->area = $area;
        $this->capacity = 0;
        $this->statutRoom = false;
        $this->showFicRoom = false;
        $this->showComment = false;
        $this->delaisMaxResaRoom = 0;
        $this->delaisMinResaRoom = 0;
        $this->allowActionInPast = false;
        $this->orderDisplay = 0;
        $this->delaisOptionReservation = 0;
        $this->dontAllowModify = false;
        $this->typeAffichageReser = 0;
        $this->quiPeutReserverPour = '';
        $this->activeRessourceEmpruntee = false;
        $this->ruleToAdd = 0;
        $this->maximumBooking = -1;
        $this->entries = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getMaximumBooking(): ?int
    {
        return $this->maximumBooking;
    }

    public function setMaximumBooking(int $maximumBooking): self
    {
        $this->maximumBooking = $maximumBooking;

        return $this;
    }

    public function getStatutRoom(): ?bool
    {
        return $this->statutRoom;
    }

    public function setStatutRoom(bool $statutRoom): self
    {
        $this->statutRoom = $statutRoom;

        return $this;
    }

    public function getShowFicRoom(): ?bool
    {
        return $this->showFicRoom;
    }

    public function setShowFicRoom(bool $showFicRoom): self
    {
        $this->showFicRoom = $showFicRoom;

        return $this;
    }

    public function getPictureRoom(): ?string
    {
        return $this->pictureRoom;
    }

    public function setPictureRoom(?string $pictureRoom): self
    {
        $this->pictureRoom = $pictureRoom;

        return $this;
    }

    public function getCommentRoom(): ?string
    {
        return $this->commentRoom;
    }

    public function setCommentRoom(?string $commentRoom): self
    {
        $this->commentRoom = $commentRoom;

        return $this;
    }

    public function getShowComment(): ?bool
    {
        return $this->showComment;
    }

    public function setShowComment(bool $showComment): self
    {
        $this->showComment = $showComment;

        return $this;
    }

    public function getDelaisMaxResaRoom(): ?int
    {
        return $this->delaisMaxResaRoom;
    }

    public function setDelaisMaxResaRoom(int $delaisMaxResaRoom): self
    {
        $this->delaisMaxResaRoom = $delaisMaxResaRoom;

        return $this;
    }

    public function getDelaisMinResaRoom(): ?int
    {
        return $this->delaisMinResaRoom;
    }

    public function setDelaisMinResaRoom(int $delaisMinResaRoom): self
    {
        $this->delaisMinResaRoom = $delaisMinResaRoom;

        return $this;
    }

    public function getAllowActionInPast(): ?bool
    {
        return $this->allowActionInPast;
    }

    public function setAllowActionInPast(bool $allowActionInPast): self
    {
        $this->allowActionInPast = $allowActionInPast;

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

    public function getDelaisOptionReservation(): ?int
    {
        return $this->delaisOptionReservation;
    }

    public function setDelaisOptionReservation(int $delaisOptionReservation): self
    {
        $this->delaisOptionReservation = $delaisOptionReservation;

        return $this;
    }

    public function getDontAllowModify(): string
    {
        return $this->dontAllowModify;
    }

    public function setDontAllowModify(bool $dontAllowModify): self
    {
        $this->dontAllowModify = $dontAllowModify;

        return $this;
    }

    public function getTypeAffichageReser(): ?int
    {
        return $this->typeAffichageReser;
    }

    public function setTypeAffichageReser(int $typeAffichageReser): self
    {
        $this->typeAffichageReser = $typeAffichageReser;

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

    public function getQuiPeutReserverPour(): ?string
    {
        return $this->quiPeutReserverPour;
    }

    public function setQuiPeutReserverPour(string $quiPeutReserverPour): self
    {
        $this->quiPeutReserverPour = $quiPeutReserverPour;

        return $this;
    }

    public function getActiveRessourceEmpruntee(): ?bool
    {
        return $this->activeRessourceEmpruntee;
    }

    public function setActiveRessourceEmpruntee(bool $activeRessourceEmpruntee): self
    {
        $this->activeRessourceEmpruntee = $activeRessourceEmpruntee;

        return $this;
    }

    public function getRuleToAdd(): ?int
    {
        return $this->ruleToAdd;
    }

    public function setRuleToAdd(int $ruleToAdd): self
    {
        $this->ruleToAdd = $ruleToAdd;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setRoom($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->contains($entry)) {
            $this->entries->removeElement($entry);
            // set the owning side to null (unless already changed)
            if ($entry->getRoom() === $this) {
                $entry->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(Authorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setRoom($this);
        }

        return $this;
    }

    public function removeAuthorization(Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getRoom() === $this) {
                $authorization->setRoom(null);
            }
        }

        return $this;
    }
}
