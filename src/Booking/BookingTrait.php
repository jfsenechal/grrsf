<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 20:24
 */

namespace App\Booking;

use Doctrine\ORM\Mapping as ORM;


trait BookingTrait
{
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $booking;

    /**
     * @var string|null
     * @ORM\Column(type="string",length=25, nullable=true)
     */
    private $booking_repeat;

    public function getBooking(): ?int
    {
        return $this->booking;
    }

    public function setBooking(?int $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getBookingRepeat(): ?string
    {
        return $this->booking_repeat;
    }

    public function setBookingRepeat(?string $booking_repeat): self
    {
        $this->booking_repeat = $booking_repeat;
    }

}