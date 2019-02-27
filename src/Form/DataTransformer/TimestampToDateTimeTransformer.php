<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 21:27
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\DataTransformer\BaseDateTimeTransformer;

class TimestampToDateTimeTransformer extends BaseDateTimeTransformer
{
    /**
     * Transforms a timestamp in the configured timezone into a DateTime object.
     *
     * @param string $value A timestamp
     *
     * @return \DateTime A \DateTime object
     *
     * @throws TransformationFailedException If the given value is not a timestamp
     *                                       or if the given timestamp is invalid
     */
    public function transform($value)
    {

        if (null === $value) {
            return;
        }

        if (!is_numeric($value)) {
            throw new TransformationFailedException('Expected a numeric.');
        }

        try {
            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone($this->outputTimezone));
            $dateTime->setTimestamp($value);

            if ($this->inputTimezone !== $this->outputTimezone) {
                $dateTime->setTimezone(new \DateTimeZone($this->inputTimezone));
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        return $dateTime;
    }

    /**
     * Transforms a DateTime object into a timestamp in the configured timezone.
     *
     * @param \DateTimeInterface $dateTime A DateTimeInterface object
     *
     * @return int A timestamp
     *
     * @throws TransformationFailedException If the given value is not a \DateTimeInterface
     */
    public function reverseTransform($dateTime)
    {
        if (null === $dateTime) {
            return;
        }

        if (!$dateTime instanceof \DateTimeInterface) {
            throw new TransformationFailedException('Expected a \DateTimeInterface.');
        }

        return $dateTime->getTimestamp();
    }
}
