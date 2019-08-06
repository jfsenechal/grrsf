<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 6/03/19
 * Time: 11:58
 */

namespace App\Model\Clock;

final class Date
{
    private const FORMAT = 'd/m/Y';
    /**
     * @var \DateTimeImmutable
     */
    private $date;

    private function __construct()
    {
        // do nothing here
    }

    public static function fromString(string $date): Date
    {
        $object = new self();

        $dateTimeImmutable = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $date
        );

        /*
         * Assert that the `createFromFormat()` didn't return
         * `false`...
         */

        $object->date = $dateTimeImmutable;

        return $object;
    }
}