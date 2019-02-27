<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrRoom
 *
 * @ORM\Table(name="grr_room")
 * @ORM\Entity(repositoryClass="App\Repository\GrrRoomRepository")
 */
class GrrRoom
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
     * @var int
     *
     * @ORM\Column(name="area_id", type="integer", nullable=false)
     */
    private $areaId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="room_name", type="string", length=60, nullable=false)
     */
    private $roomName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=60, nullable=false)
     */
    private $description = '';

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="integer", nullable=false)
     */
    private $capacity = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="max_booking", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $maxBooking = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="statut_room", type="string", length=1, nullable=false, options={"default"="1","fixed"=true})
     */
    private $statutRoom = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="show_fic_room", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $showFicRoom = 'n';

    /**
     * @var string
     *
     * @ORM\Column(name="picture_room", type="string", length=50, nullable=false)
     */
    private $pictureRoom = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_room", type="text", length=65535, nullable=false)
     */
    private $commentRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="show_comment", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $showComment = 'n';

    /**
     * @var int
     *
     * @ORM\Column(name="delais_max_resa_room", type="smallint", nullable=false, options={"default"="-1"})
     */
    private $delaisMaxResaRoom = '-1';

    /**
     * @var int
     *
     * @ORM\Column(name="delais_min_resa_room", type="smallint", nullable=false)
     */
    private $delaisMinResaRoom = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="allow_action_in_past", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $allowActionInPast = 'n';

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
     */
    private $orderDisplay = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="delais_option_reservation", type="smallint", nullable=false)
     */
    private $delaisOptionReservation = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="dont_allow_modify", type="string", length=1, nullable=false, options={"default"="n"})
     */
    private $dontAllowModify = 'n';

    /**
     * @var int
     *
     * @ORM\Column(name="type_affichage_reser", type="smallint", nullable=false)
     */
    private $typeAffichageReser = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="moderate", type="boolean", nullable=true)
     */
    private $moderate = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="qui_peut_reserver_pour", type="string", length=1, nullable=false, options={"default"="5"})
     */
    private $quiPeutReserverPour = '5';

    /**
     * @var string
     *
     * @ORM\Column(name="active_ressource_empruntee", type="string", length=1, nullable=false, options={"default"="y","fixed"=true})
     */
    private $activeRessourceEmpruntee = 'y';

    /**
     * @var int
     *
     * @ORM\Column(name="who_can_see", type="smallint", nullable=false)
     */
    private $whoCanSee = '0';

    public function __toString()
    {
        return $this->roomName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAreaId(): ?int
    {
        return $this->areaId;
    }

    public function setAreaId(int $areaId): self
    {
        $this->areaId = $areaId;

        return $this;
    }

    public function getRoomName(): ?string
    {
        return $this->roomName;
    }

    public function setRoomName(string $roomName): self
    {
        $this->roomName = $roomName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
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

    public function getStatutRoom(): ?string
    {
        return $this->statutRoom;
    }

    public function setStatutRoom(string $statutRoom): self
    {
        $this->statutRoom = $statutRoom;

        return $this;
    }

    public function getShowFicRoom(): ?string
    {
        return $this->showFicRoom;
    }

    public function setShowFicRoom(string $showFicRoom): self
    {
        $this->showFicRoom = $showFicRoom;

        return $this;
    }

    public function getPictureRoom(): ?string
    {
        return $this->pictureRoom;
    }

    public function setPictureRoom(string $pictureRoom): self
    {
        $this->pictureRoom = $pictureRoom;

        return $this;
    }

    public function getCommentRoom(): ?string
    {
        return $this->commentRoom;
    }

    public function setCommentRoom(string $commentRoom): self
    {
        $this->commentRoom = $commentRoom;

        return $this;
    }

    public function getShowComment(): ?string
    {
        return $this->showComment;
    }

    public function setShowComment(string $showComment): self
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

    public function getAllowActionInPast(): ?string
    {
        return $this->allowActionInPast;
    }

    public function setAllowActionInPast(string $allowActionInPast): self
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

    public function getDontAllowModify(): ?string
    {
        return $this->dontAllowModify;
    }

    public function setDontAllowModify(string $dontAllowModify): self
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

    public function getActiveRessourceEmpruntee(): ?string
    {
        return $this->activeRessourceEmpruntee;
    }

    public function setActiveRessourceEmpruntee(string $activeRessourceEmpruntee): self
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


}
