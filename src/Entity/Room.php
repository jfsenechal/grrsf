<?php

namespace App\Entity;

use App\Entity\Security\AdministratorResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Room.
 *
 * @ORM\Table(name="grr_room")
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
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
     * @var
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="rooms")
     */
    private $area;

    /**
     * @var string
     *@Assert\NotBlank()
     * @ORM\Column(name="room_name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=60, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="integer", nullable=false)
     */
    private $capacity;

    /**
     * @var int
     *
     * @ORM\Column(name="max_booking", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $maxBooking;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_room", type="boolean")
     */
    private $statutRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="show_fic_room", type="boolean", nullable=false, options={"default"="0"})
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
     * @var string
     *
     * @ORM\Column(name="show_comment", type="boolean", nullable=false, options={"default"="0"})
     */
    private $showComment;

    /**
     * @var int
     *
     * @ORM\Column(name="delais_max_resa_room", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $delaisMaxResaRoom;

    /**
     * @var int
     *
     * @ORM\Column(name="delais_min_resa_room", type="smallint", nullable=false)
     */
    private $delaisMinResaRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="allow_action_in_past", type="boolean", nullable=false, options={"default"="0"})
     */
    private $allowActionInPast;

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
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
     * @ORM\Column(name="dont_allow_modify", type="boolean", nullable=false, options={"default"="0"})
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
     * @ORM\Column(name="qui_peut_reserver_pour", type="string", length=1, nullable=false, options={"default"="5"})
     */
    private $quiPeutReserverPour;

    /**
     * @var string
     *
     * @ORM\Column(name="active_ressource_empruntee", type="boolean", nullable=false, options={"default"="0"})
     */
    private $activeRessourceEmpruntee;

    /**
     * @var int
     *
     * @ORM\Column(name="who_can_see", type="smallint", nullable=false)
     */
    private $whoCanSee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="room", cascade={"remove"})
     */
    private $entries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\AdministratorResource", mappedBy="room", orphanRemoval=true)
     */
    private $administratorResources;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->administratorResources = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

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

    public function getDontAllowModify(): ?bool
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

    public function getWhoCanSee(): ?int
    {
        return $this->whoCanSee;
    }

    public function setWhoCanSee(int $whoCanSee): self
    {
        $this->whoCanSee = $whoCanSee;

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
     * @return Collection|AdministratorResource[]
     */
    public function getAdministratorResources(): Collection
    {
        return $this->administratorResources;
    }

    public function addAdministratorResource(AdministratorResource $administratorResource): self
    {
        if (!$this->administratorResources->contains($administratorResource)) {
            $this->administratorResources[] = $administratorResource;
            $administratorResource->setRoom($this);
        }

        return $this;
    }

    public function removeAdministratorResource(AdministratorResource $administratorResource): self
    {
        if ($this->administratorResources->contains($administratorResource)) {
            $this->administratorResources->removeElement($administratorResource);
            // set the owning side to null (unless already changed)
            if ($administratorResource->getRoom() === $this) {
                $administratorResource->setRoom(null);
            }
        }

        return $this;
    }
}
