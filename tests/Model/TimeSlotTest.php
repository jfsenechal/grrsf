<?php

namespace App\Tests\Model;

use App\Model\TimeSlot;
use App\Tests\BaseTesting;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TimeSlotTest extends BaseTesting
{
    public function testTimeSlot()
    {
        $begin = Carbon::create(2019, 10, 01, 9, 10);
        $end = Carbon::create(2019, 10, 01, 17, 00);

        $timeSlot = new TimeSlot($begin, $end);
        $timeSlot->getBegin();
        $timeSlot->getBegin();

        $this->assertSame($timeSlot->getBegin(), $begin);
        $this->assertSame($timeSlot->getEnd(), $end);
    }
}
