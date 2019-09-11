<?php

namespace App\Security\Voter;

use App\Entity\Entry;
use App\Entity\Security\User;
use App\Security\SecurityHelper;
use App\Security\SecurityRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 *
 */
class EntryVoter extends Voter
{
    const NEW = 'grr.entry.new';
    const SHOW = 'grr.entry.show';
    const EDIT = 'grr.entry.edit';
    const DELETE = 'grr.entry.delete';
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
     * @var Entry
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


        if (!$this->isAnonyme() && $user->hasRole(SecurityRole::ROLE_GRR_ADMINISTRATOR)) {
            return true;
        }

        /**
         * not work with test
         */
        if ($this->decisionManager->decide($token, [SecurityRole::ROLE_GRR_ADMINISTRATOR])) {
            // return true;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->canView();
            case self::EDIT:
                return $this->canEdit();
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    /**
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

        return $this->securityHelper->canAddEntry($room, $this->user);
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
