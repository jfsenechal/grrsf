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
    /**
     * @var Security
     */
    private $security;

    public function __construct(ParameterBagInterface $parameterBag, Security $security, RequestStack $requestStack)
    {
        $this->parameterBag = $parameterBag;
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->setDefaultLocal();
    }

    public function setDefaultLocal(): void
    {
        /**
         * @var User
         */
        $user = $this->security->getUser();
        /*
         * Parameter from config symfony framework.yaml
         * */
        self::$defaultLocale = $this->parameterBag->get('locale');
        /**
         * Navigator.
         */
        $master = $this->requestStack->getMasterRequest();
        if ($master) {
            self::$defaultLocale = $master->getLocale();
        }
        /*
         * user preference
         */
        if ($user) {
            if ($user->getLanguageDefault()) {
                self::$defaultLocale = $user->getLanguageDefault();
            }
        }
    }

    public static function getDefaultLocal(): string
    {
        return self::$defaultLocale;
    }
}
