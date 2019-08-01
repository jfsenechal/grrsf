<?php

namespace App\Service;

use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocalHelper
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var string
     */
    private static $defaultLocale;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        self::$defaultLocale = $this->parameterBag->get('locale');
    }

    public static function getDefaultLocal()
    {
        return self::$defaultLocale;
    }

}