<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrOverload
 *
 * @ORM\Table(name="grr_overload")
 * @ORM\Entity
 */
class GrrOverload
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
     * @ORM\Column(name="id_area", type="integer", nullable=false)
     */
    private $idArea;

    /**
     * @var string
     *
     * @ORM\Column(name="fieldname", type="string", length=25, nullable=false)
     */
    private $fieldname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="fieldtype", type="string", length=25, nullable=false)
     */
    private $fieldtype = '';

    /**
     * @var string
     *
     * @ORM\Column(name="fieldlist", type="text", length=65535, nullable=false)
     */
    private $fieldlist;

    /**
     * @var string
     *
     * @ORM\Column(name="obligatoire", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $obligatoire = 'n';

    /**
     * @var string
     *
     * @ORM\Column(name="affichage", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $affichage = 'n';

    /**
     * @var string
     *
     * @ORM\Column(name="overload_mail", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $overloadMail = 'n';

    /**
     * @var string
     *
     * @ORM\Column(name="confidentiel", type="string", length=1, nullable=false, options={"default"="n","fixed"=true})
     */
    private $confidentiel = 'n';

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFieldname(): ?string
    {
        return $this->fieldname;
    }

    public function setFieldname(string $fieldname): self
    {
        $this->fieldname = $fieldname;

        return $this;
    }

    public function getFieldtype(): ?string
    {
        return $this->fieldtype;
    }

    public function setFieldtype(string $fieldtype): self
    {
        $this->fieldtype = $fieldtype;

        return $this;
    }

    public function getFieldlist(): ?string
    {
        return $this->fieldlist;
    }

    public function setFieldlist(string $fieldlist): self
    {
        $this->fieldlist = $fieldlist;

        return $this;
    }

    public function getObligatoire(): ?string
    {
        return $this->obligatoire;
    }

    public function setObligatoire(string $obligatoire): self
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }

    public function getAffichage(): ?string
    {
        return $this->affichage;
    }

    public function setAffichage(string $affichage): self
    {
        $this->affichage = $affichage;

        return $this;
    }

    public function getOverloadMail(): ?string
    {
        return $this->overloadMail;
    }

    public function setOverloadMail(string $overloadMail): self
    {
        $this->overloadMail = $overloadMail;

        return $this;
    }

    public function getConfidentiel(): ?string
    {
        return $this->confidentiel;
    }

    public function setConfidentiel(string $confidentiel): self
    {
        $this->confidentiel = $confidentiel;

        return $this;
    }


}
