<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 5/03/19
 * Time: 22:00
 */

namespace App\Service\Clock;


final class SystemClock implements Clock
{
    public function currentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}