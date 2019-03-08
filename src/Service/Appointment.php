<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 6/03/19
 * Time: 21:11
 */

namespace App\Service;

use DateTime;

final class Appointment
{
    /**
     * @var DateTime
     */
    private $time;

    public function __construct(DateTime $time)
    {
        $this->time = $time;
    }

    public function time(): string
    {
        return $this->time->format('h:s');
    }
}