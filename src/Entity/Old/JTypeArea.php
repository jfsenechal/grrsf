<?php

namespace App\Entity\Old;

/**
 * JTypeArea.
 *
 * ORM\Table(name="grr_j_type_area")
 * ORM\Entity()
 */
class JTypeArea
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
     * ORM\Column(name="id_type", type="integer", nullable=false)
     */
    private $idType = '0';

    /**
     * @var int
     *
     * ORM\Column(name="id_area", type="integer", nullable=false)
     */
    private $idArea = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setIdType(int $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdArea(): ?int
    {
        return $this->idArea;
    }

    public function setIdArea(int $idArea): self
    {
        $this->idArea = $idArea;

        return $this;
    }
}
