<?php

namespace App\I18n;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ParameterBagInterface $parameterBag, RequestStack $requestStack)
    {
        $this->parameterBag = $parameterBag;
        $this->requestStack = $requestStack;
        self::$defaultLocale = $this->parameterBag->get('locale');
        $locale = $this->requestStack->getMasterRequest()->getLocale();//navigateur
    }

    public static function getDefaultLocal()
    {
        return self::$defaultLocale;
    }
}
