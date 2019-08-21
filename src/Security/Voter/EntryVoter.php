<?php

namespace App\Security\Voter;

use App\Entity\Entry;
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
class EntryVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"
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
     * @var Entry $entry
     */
    private $entry;
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
            if (!$subject instanceof Entry) {
                return false;
            }
        }

        return in_array($attribute, [self::NEW, self::SHOW, self::EDIT, self::DELETE], true);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $entry, TokenInterface $token)
    {
        $user = $token->getUser();
        $this->user = $user;
        $this->entry = $entry;
        $this->token = $token;

        if ($this->decisionManager->decide($token, [SecurityData::getRoleGrrAdministrator()])) {
            return true;
        }

        switch ($attribute) {
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

    private function canNew()
    {
        if ($this->canEdit()) {
            return true;
        }

        $room = $this->entry->getRoom();

        return $this->securityHelper->canAddEntry($this->user, $room);
    }

    /**
     *
     * @return bool
     */
    private function canView()
    {
        $area = $this->entry->getRoom()->getArea();
        $room = $this->entry->getRoom();

        if ($this->isAnonyme()) {
            if ($this->securityHelper->isAreaRestricted($area)) {
                return false;
            }

            return $this->securityHelper->canSeeRoom($room, null);
        }

        if ($this->securityHelper->isAreaRestricted($area)) {
            return $this->securityHelper->canSeeAreaRestricted($area, $this->user);
        }
        return $this->securityHelper->canSeeRoom($room, $this->user);
    }

    private function canEdit()
    {
        $room = $this->entry->getRoom();

        return $this->securityHelper->canAddEntry($this->user, $room);
    }

    private function canDelete()
    {
        return (bool)$this->canEdit();
    }

    private function isAnonyme(): bool
    {
        return !($this->user instanceof User);
    }
}
