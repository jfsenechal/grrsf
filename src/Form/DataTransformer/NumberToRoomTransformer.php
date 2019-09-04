<?php

namespace App\Form\DataTransformer;

use App\Entity\Area;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToRoomTransformer implements DataTransformerInterface
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct()
    {
      //  $this->roomRepository = $roomRepository;
    }

    /**
     * Transforms an object (area) to a string (number).
     *
     * @param Area|null $area
     *
     * @return string
     */
    public function transform($area)
    {dump($area);
        if (null === $area) {
            return '';
        }

        return $area->getId();
    }

    /**
     * Transforms a string (number) to an object (area).
     *
     * @param string $areaNumber
     *
     * @return Area|null
     *
     * @throws TransformationFailedException if object (area) is not found
     */
    public function reverseTransform($areaNumber)
    {dump($areaNumber);
        // no area number? It's optional, so that's ok
        if (!$areaNumber) {
            return;
        }

        $area = $this->roomRepository->find($areaNumber);

        if (null === $area) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(
                sprintf(
                    'An area with number "%s" does not exist!',
                    $areaNumber
                )
            );
        }

        return $area;
    }
}
