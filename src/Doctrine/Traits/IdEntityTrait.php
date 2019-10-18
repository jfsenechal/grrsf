<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 4/03/19
 * Time: 14:45.
 */

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdEntityTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
