<?php

namespace App\Entity;

use App\Doctrine\Traits\IdEntityTrait;
use App\Entity\Security\UserAuthorization;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Area.
 *
 * @ORM\Table(name="area")
 * @ORM\Entity(repositoryClass="App\Repository\AreaRepository")
 */
class Area
{
    use IdEntityTrait;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $order_display;

    /**
     * @var int
     * @Assert\LessThan(propertyPath="end_time", message="area.constraint.start_smaller_end")
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $start_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $end_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $week_start;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_24_hour_format;
    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $days_of_week_to_display;

    /**
     * Intervalle de temps.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $time_interval;

    /**
     * Durée maximum qu'un utilisateur peut réserver.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_maximum_entry;

    /**
     * Durée par défaut d'une réservation.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_default_entry;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $minutes_to_add_to_end_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $max_booking;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_restricted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Room", mappedBy="area")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\UserAuthorization", mappedBy="area", orphanRemoval=true)
     */
    private $authorizations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EntryType")
     */
    private $entryTypes;

    public function __construct()
    {
        $this->start_time = 8;
        $this->end_time = 19;
        $this->is_24_hour_format = true;
        $this->order_display = 0;
        $this->week_start = 0;
        $this->days_of_week_to_display = [1,2,3,4,5];
        $this->time_interval = 30;
        $this->duration_default_entry = 30;
        $this->duration_maximum_entry = -1;
        $this->minutes_to_add_to_end_time = 0;
        $this->max_booking = -1;
        $this->is_restricted = false;
        $this->rooms = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

}
