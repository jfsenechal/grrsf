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
    private $defaultLocale;
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
    }

    public function setDefaultLocal(): void
    {
        /**
         * @var User
         */
        $user = $this->security->getUser();
        /*
         * Parameter from symfony config/translation.yaml
         * */
        $this->defaultLocale = $this->parameterBag->get('locale');
        /**
         * Navigator.
         */
        $master = $this->requestStack->getMasterRequest();
        if ($master !== null) {
            $this->defaultLocale = $master->getLocale();
        }
        /**
         * user preference
         */
        if ($user) {
            if ($user->getLanguageDefault()) {
                $this->defaultLocale = $user->getLanguageDefault();
            }
        }
    }

    public function getDefaultLocal(): string
    {
        if ($this->defaultLocale === '' || $this->defaultLocale === null) {
            $this->setDefaultLocal();
        }

        if ($this->defaultLocale !== '' && $this->defaultLocale !== null) {
            return $this->defaultLocale;
        }

        return 'fr'; //bug test mode console
    }
}
