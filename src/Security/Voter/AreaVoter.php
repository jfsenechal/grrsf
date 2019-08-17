<?php

namespace App\Security\Voter;

use App\Entity\Area;
use App\Entity\Security\User;
use App\Security\SecurityData;
use App\Security\SecurityHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * It grants or denies permissions for actions related to blog posts (such as
 * showing, editing and deleting posts).
 *
 * See http://symfony.com/doc/current/security/voters.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class AreaVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"
    const INDEX = 'index';
    const NEW = 'new';
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * @var User
     */
    private $user;
    /**
     * @var SecurityHelper
     */
    private $securityHelper;
    /**
     * @var Area $area
     */
    private $area;
    /**
     * @var TokenInterface
     */
    private $token;

    public function __construct(AccessDecisionManagerInterface $decisionManager, SecurityHelper $securityHelper)
    {
        $this->decisionManager = $decisionManager;
        $this->securityHelper = $securityHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if ($subject) {
            if (!$subject instanceof Area) {
                return false;
            }
        }

        return in_array($attribute, [self::INDEX, self::NEW, self::SHOW, self::EDIT, self::DELETE], true);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $area, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $this->user = $user;
        $this->area = $area;
        $this->token = $token;

        if ($this->decisionManager->decide($token, [SecurityData::getRoleAdministrator()])) {
            return true;
        }

        switch ($attribute) {
            case self::INDEX:
                return $this->canIndex();
            case self::NEW:
                return $this->canNew();
            case self::SHOW:
                return $this->canView();
            case self::EDIT:
                return $this->canEdit();
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    private function canIndex()
    {
        if ($this->decisionManager->decide($this->token, [SecurityData::getRoleManagerArea()])) {
            return true;
        }
        if ($this->decisionManager->decide($this->token, [SecurityData::getRoleManagerArea()])) {
            return true;
        }

        return false;
    }

    private function canNew()
    {
        if ($this->decisionManager->decide($this->token, [SecurityData::getRoleManagerArea()])) {
            return true;
        }
        if ($this->decisionManager->decide($this->token, [SecurityData::getRoleManagerArea()])) {
            return true;
        }

        return false;
    }

    /**
     * See in admin.
     * @return bool
     */
    private function canView()
    {
        if ($this->canEdit()) {
            return true;
        }

        return $this->securityHelper->isAreaManager($this->user, $this->area);
    }

    private function canEdit()
    {
        return $this->securityHelper->isAreaAdministrator($this->user, $this->area);
    }

    private function canDelete()
    {
        return (bool)$this->canEdit();
    }
}
