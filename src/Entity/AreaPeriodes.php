<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AreaPeriodes
 *
 * @ORM\Table(name="grr_area_periodes")
 * @ORM\Entity
 */
class AreaPeriodes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_area", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idArea;

    /**
     * @var int
     *
     * @ORM\Column(name="num_periode", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numPeriode;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_periode", type="string", length=100, nullable=false)
     */
    private $nomPeriode;

    public function getIdArea(): ?int
    {
        return $this->idArea;
    }

    public function getNumPeriode(): ?int
    {
        return $this->numPeriode;
    }

    public function getNomPeriode(): ?string
    {
        return $this->nomPeriode;
    }

    public function setNomPeriode(string $nomPeriode): self
    {
        $this->nomPeriode = $nomPeriode;

        return $this;
    }


}
