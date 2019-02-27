<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrCalendar
 *
 * @ORM\Table(name="grr_calendar")
 * @ORM\Entity
 */
class GrrCalendar
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
     * @ORM\Column(name="DAY", type="integer", nullable=false)
     */
    private $day = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }


}
