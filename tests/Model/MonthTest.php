<?php


namespace App\Tests\Model;


use App\Model\Month;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MonthTest extends WebTestCase
{
    public function testCreateNew()
    {
        $month = Month::init(2019, 10, 01);

        $this->assertInstanceOf(Month::class, $month);
        $this->assertSame('2019-10-01', $month->firstOfMonth()->format('Y-m-d'));
        $this->assertSame('2018', $month->previousYear()->format('Y'));
        $this->assertSame('2020', $month->nextYear()->format('Y'));
        $this->assertSame('09', $month->previousMonth()->format('m'));
        $this->assertSame('11', $month->nextMonth()->format('m'));
        $i = 1;
        foreach ($month->getCalendarDays() as $day) {
            $this->assertEquals($i, $day->day);
            $i++;
        }

        foreach ($month->getWeeksOfMonth() as $week) {
            foreach ($week as $day) {
                $this->assertContains($day->format('Y-m-d'), $this->getDays());
            }
        }
    }

    protected function getDays()
    {
        return [
            "2019-09-30",
            "2019-10-01",
            "2019-10-02",
            "2019-10-03",
            "2019-10-04",
            "2019-10-05",
            "2019-10-06",
            "2019-10-07",
            "2019-10-08",
            "2019-10-09",
            "2019-10-10",
            "2019-10-11",
            "2019-10-12",
            "2019-10-13",
            "2019-10-14",
            "2019-10-15",
            "2019-10-16",
            "2019-10-17",
            "2019-10-18",
            "2019-10-19",
            "2019-10-20",
            "2019-10-21",
            "2019-10-22",
            "2019-10-23",
            "2019-10-24",
            "2019-10-25",
            "2019-10-26",
            "2019-10-27",
            "2019-10-28",
            "2019-10-29",
            "2019-10-30",
            "2019-10-31",
            "2019-11-01",
            "2019-11-02",
            "2019-11-03",
        ];
    }
}