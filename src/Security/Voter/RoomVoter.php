<?php

namespace App\Security\Voter;

use App\Entity\Room;
use App\Entity\Security\User;
use App\Security\SecurityRole;
use App\Security\SecurityHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 *
 */
class RoomVoter extends Voter
{
    const INDEX = 'grr.room.index';
    const ADD_ENTRY = 'grr.addEntry';
    const SHOW = 'grr.room.show';
    const EDIT = 'grr.room.edit';
    const DELETE = 'grr.room.delete';
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
     * @var Room
     */
    private $room;
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
            if (!$subject instanceof Room) {
                return false;
            }
        }

        return in_array(
            $attribute,
            [self::INDEX, self::ADD_ENTRY, self::SHOW, self::EDIT, self::DELETE],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $room, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $this->user = $user;
        $this->room = $room;
        $this->token = $token;

        if ($user->hasRole(SecurityRole::ROLE_GRR_ADMINISTRATOR)) {
            return true;
        }

        /**
         * not work with test
         */
        if ($this->decisionManager->decide($token, [SecurityRole::ROLE_GRR_ADMINISTRATOR])) {
            //    return true;
        }

        switch ($attribute) {
            case self::INDEX:
                return $this->canIndex();
            case self::ADD_ENTRY:
                return $this->canAddEntry();
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
     * No rule
     * @return bool
     */
    private function canIndex(): bool
    {
        return true;
    }

    private function canAddEntry()
    {
        return $this->securityHelper->canAddEntry($this->room, $this->user);
    }

    /**
     * See in admin.
     *
     * @return bool
     */
    private function canView(): bool
    {
        if ($this->canEdit()) {
            return true;
        }

        return $this->securityHelper->isRoomManager($this->user, $this->room);
    }

    private function canEdit(): bool
    {
        return $this->securityHelper->isRoomAdministrator($this->user, $this->room);
    }

    private function canDelete(): bool
    {
        return $this->canEdit();
    }
}
