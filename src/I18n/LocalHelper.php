<?php

namespace App\I18n;

use App\Entity\Security\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

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

    public function __construct(ParameterBagInterface $parameterBag, Security $security, RequestStack $requestStack)
    {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        $this->parameterBag = $parameterBag;
        $this->requestStack = $requestStack;
        //parameter config symfony
        self::$defaultLocale = $this->parameterBag->get('locale');
        $master = $this->requestStack->getMasterRequest();
        if ($master) {
            $locale = $master->getLocale(); //navigateur
        }
        if ($user) {
            $locale = $user->getLanguageDefault();
        }
    }

    public static function getDefaultLocal()
    {
        return self::$defaultLocale;
    }
}
