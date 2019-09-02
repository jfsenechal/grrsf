<?php

namespace App\Form\DataTransformer;

use App\Entity\Area;
use App\Repository\AreaRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AreaToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * Transforms an object (area) to a string (number).
     *
     * @param Area|null $area
     *
     * @return string
     */
    public function transform($area)
    {
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
    {
        // no area number? It's optional, so that's ok
        if (!$areaNumber) {
            return;
        }

        $area = $this->areaRepository->find($areaNumber);

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
