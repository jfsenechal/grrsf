<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 5/03/19
 * Time: 22:03
 */

namespace App\Model\Clock;

/**
 * $meetupRepository = new MeetupRepository(
 * new FixedClock(
 * new DateTimeImmutable('2018-12-24 11:16:05')
 * )
 * );
 *
 * mieux mettre comme argument de fonction
 *
 * Class FixedClock
 * @package App\Service\Clock
 */
final class FixedClock implements Clock
{
    /**
     * @var \DateTimeImmutable
     */
    private $now;

    public function __construct(\DateTimeImmutable $now)
    {
        $this->now = $now;

    }

    public function currentTime(): \DateTimeImmutable
    {
        return $this->now;
    }
}
