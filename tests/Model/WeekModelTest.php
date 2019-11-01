<?php

namespace App\Tests\Model;

use App\Model\Week;
use App\Tests\BaseTesting;

class WeekModelTest extends BaseTesting
{
    public function testCreate()
    {
        $week = Week::create(2019, 34, 'fr');
        //todo bug
        // PHPUnit\Framework\Exception: PHP Fatal error:  Uncaught Exception: Serialization of 'Closure' is not allowed in Standard input code:79
        //$week = Week::create(2019, 34, 'en');

        $this->assertInstanceOf(Week::class, $week);
        $this->assertSame('2019-08-19', $week->getFirstDay()->toDateString());
        $this->assertSame('2019-08-25', $week->getLastDay()->toDateString());
        foreach ($week->getCalendarDays() as $day) {
            $this->assertTrue(in_array($day->toDateString(), $this->getDays()));
        }
    }

    public function getDays()
    {
        return [
            '2019-08-19',
            '2019-08-20',
            '2019-08-21',
            '2019-08-22',
            '2019-08-23',
            '2019-08-24',
            '2019-08-25',
        ];
    }
}
