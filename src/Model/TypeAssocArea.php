<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 25/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Model;

use App\Entity\Area;
use App\Entity\EntryType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TypeAssocArea
{
    /**
     * @var EntryType[]
     */
    protected $types;

    /**
     * @var Area
     */
    protected $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
        $this->types = new ArrayCollection();
    }
    
     /**
     * @return Collection|EntryType[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(EntryType $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(EntryType $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }

        return $this;
    }

    /**
     * @param mixed $types
     */
    public function setTypes($types): void
    {
        $this->types = $types;
    }

    /**
     * @return Area
     */
    public function getArea(): Area
    {
        return $this->area;
    }

    /**
     * @param Area $area
     */
    public function setArea(Area $area): void
    {
        $this->area = $area;
    }

}