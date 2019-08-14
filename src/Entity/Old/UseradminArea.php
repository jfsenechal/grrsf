<?php

namespace App\Entity\Old;

use Doctrine\ORM\Mapping as ORM;

/**
 * UseradminArea.
 *
 * ORM\Table(name="grr_j_useradmin_area")
 * ORM\Entity()
 */
class UseradminArea
{
    /**
     * @var string
     *
     * ORM\Column(name="login", type="string", length=20, nullable=false)
     * ORM\Id
     * ORM\GeneratedValue(strategy="NONE")
     */
    private $login = '';

    /**
     * @var int
     *
     * ORM\Column(name="id_area", type="integer", nullable=false)
     * ORM\Id
     * ORM\GeneratedValue(strategy="NONE")
     */
    private $idArea = '0';

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getIdArea(): ?int
    {
        return $this->idArea;
    }
}
