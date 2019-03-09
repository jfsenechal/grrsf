<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * GrrSetting
 *
 * @ORM\Table(name="grr_setting")
 * @ORM\Entity(repositoryClass="App\Repository\GrrSettingRepository")
 */
class GrrSetting
{
    use IdEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="NAME", type="string", length=32, nullable=false, unique=true)
     * ORM\Id
     * ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="VALUE", type="text", length=65535, nullable=false)
     */
    private $value;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


}
