<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 6/03/19
 * Time: 21:09
 */

namespace App\Service;


final class Year
{
    private $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function next(): Year
    {
        return new self($this->year + 1);
    }
}