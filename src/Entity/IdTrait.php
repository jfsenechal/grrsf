<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 4/03/19
 * Time: 14:45
 */

namespace App\Entity;


trait IdTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}