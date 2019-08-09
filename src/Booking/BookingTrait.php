<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 20:24.
 */

namespace App\Booking;

use Doctrine\ORM\Mapping as ORM;

trait BookingTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $booking;

    /**
     * @return string|null
     */
    public function getBooking(): ?string
    {
        return $this->booking;
    }

    /**
     * @param string|null $booking
     *
     * @return self
     */
    public function setBooking(?string $booking): self
    {
        $this->booking = $booking;

        return $this;
    }
}
