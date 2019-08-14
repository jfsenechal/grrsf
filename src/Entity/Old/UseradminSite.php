<?php

namespace App\Entity\Old;

use Doctrine\ORM\Mapping as ORM;

/**
 * UseradminSite.
 *
 * ORM\Table(name="grr_j_useradmin_site")
 * ORM\Entity
 */
class UseradminSite
{
    /**
     * @var string
     *
     * ORM\Column(name="login", type="string", length=40, nullable=false)
     * ORM\Id
     * ORM\GeneratedValue(strategy="NONE")
     */
    private $login = '';

    /**
     * @var int
     *
     * ORM\Column(name="id_site", type="integer", nullable=false)
     * ORM\Id
     * ORM\GeneratedValue(strategy="NONE")
     */
    private $idSite = '0';

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getIdSite(): ?int
    {
        return $this->idSite;
    }
}
