<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JSiteArea.
 *
 * @ORM\Table(name="grr_j_site_area")
 * @ORM\Entity
 */
class JSiteArea
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_site", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSite = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="id_area", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idArea = '0';

    public function getIdSite(): ?int
    {
        return $this->idSite;
    }

    public function getIdArea(): ?int
    {
        return $this->idArea;
    }
}
