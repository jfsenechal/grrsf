<?php

namespace App\Entity\Old;

/**
 * CalendrierJoursCycle.
 *
 * ORM\Table(name="grr_calendrier_jours_cycle")
 * ORM\Entity
 */
class CalendrierJoursCycle
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
     * @var int
     *
     * ORM\Column(name="DAY", type="integer", nullable=false)
     */
    private $day = '0';

    /**
     * @var string|null
     *
     * ORM\Column(name="Jours", type="string", length=20, nullable=true)
     */
    private $jours;

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

    public function getJours(): ?string
    {
        return $this->jours;
    }

    public function setJours(?string $jours): self
    {
        $this->jours = $jours;

        return $this;
    }
}
