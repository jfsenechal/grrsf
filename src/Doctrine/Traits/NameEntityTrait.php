<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 2/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait NameEntityTrait
{
    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}