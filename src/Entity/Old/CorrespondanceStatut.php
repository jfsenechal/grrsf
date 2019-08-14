<?php

namespace App\Entity\Old;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorrespondanceStatut.
 *
 * @ORM\Table(name="grr_correspondance_statut")
 * @ORM\Entity
 */
class CorrespondanceStatut
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
     * @var string
     *
     * @ORM\Column(name="code_fonction", type="string", length=30, nullable=false)
     */
    private $codeFonction;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_fonction", type="string", length=200, nullable=false)
     */
    private $libelleFonction;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_grr", type="string", length=30, nullable=false)
     */
    private $statutGrr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeFonction(): ?string
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(string $codeFonction): self
    {
        $this->codeFonction = $codeFonction;

        return $this;
    }

    public function getLibelleFonction(): ?string
    {
        return $this->libelleFonction;
    }

    public function setLibelleFonction(string $libelleFonction): self
    {
        $this->libelleFonction = $libelleFonction;

        return $this;
    }

    public function getStatutGrr(): ?string
    {
        return $this->statutGrr;
    }

    public function setStatutGrr(string $statutGrr): self
    {
        $this->statutGrr = $statutGrr;

        return $this;
    }
}
