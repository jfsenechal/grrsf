<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 5/03/19
 * Time: 22:00
 */

namespace App\Model\Clock;

interface Clock
{
    public function currentTime(): \DateTimeImmutable;
}