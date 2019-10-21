<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Doctrine\Traits\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Setting.
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 * @ApiResource
 */
class Setting
{
    use IdEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $value;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $required;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->required = false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
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

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}
