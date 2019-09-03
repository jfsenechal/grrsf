<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use App\Entity\Security\UserAuthorization;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Room.
 *
 * @ORM\Table(name="grr_room")
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{
    use IdEntityTrait;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $name;

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
    private $maximum_booking;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $statutRoom;

    /**
     * @var string
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
     * @var string
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
    private $order_display;

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
     * @ORM\Column(name="who_can_see", type="smallint", nullable=false)
     */
    private $whoCanSee;

    /**
     * @var Area
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $area;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="room", cascade={"remove"})
     */
    private $entries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\UserAuthorization", mappedBy="room", orphanRemoval=true)
     */
    private $authorizations;

    public function __construct(Area $area)
    {
        $this->area = $area;
        $this->capacity = 0;
        $this->statutRoom = '';
        $this->showFicRoom = '';
        $this->showComment = '';
        $this->delaisMaxResaRoom = 0;
        $this->delaisMinResaRoom = 0;
        $this->allowActionInPast = false;
        $this->order_display = 0;
        $this->delaisOptionReservation = 0;
        $this->dontAllowModify = '';
        $this->typeAffichageReser = 0;
        $this->quiPeutReserverPour = '';
        $this->activeRessourceEmpruntee = false;
        $this->whoCanSee = 0;
        $this->maximum_booking = -1;
        $this->entries = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getMaximumBooking(): ?int
    {
        return $this->maximum_booking;
    }

    public function setMaximumBooking(int $maximum_booking): self
    {
        $this->maximum_booking = $maximum_booking;

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
        return $this->order_display;
    }

    public function setOrderDisplay(int $order_display): self
    {
        $this->order_display = $order_display;

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

    public function getArea(): Area
    {
        return $this->area;
    }

    public function setArea(Area $area): self
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
     * @return Collection|UserAuthorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addUsersManagerResource(UserAuthorization $usersManagerResource): self
    {
        if (!$this->authorizations->contains($usersManagerResource)) {
            $this->authorizations[] = $usersManagerResource;
            $usersManagerResource->setRoom($this);
        }

        return $this;
    }

    public function removeUsersManagerResource(UserAuthorization $usersManagerResource): self
    {
        if ($this->authorizations->contains($usersManagerResource)) {
            $this->authorizations->removeElement($usersManagerResource);
            // set the owning side to null (unless already changed)
            if ($usersManagerResource->getRoom() === $this) {
                $usersManagerResource->setRoom(null);
            }
        }

        return $this;
    }

    public function addAuthorization(UserAuthorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setRoom($this);
        }

        return $this;
    }

    public function removeAuthorization(UserAuthorization $authorization): self
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
