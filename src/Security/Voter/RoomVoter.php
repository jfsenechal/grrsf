<?php

namespace App\Security\Voter;

use App\Entity\Room;
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
class RoomVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"
    const NEW = 'new';
    const ADD_ENTRY = 'addEntry';
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
     * @var Room $room
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

        return in_array($attribute, [self::NEW, self::ADD_ENTRY, self::SHOW, self::EDIT, self::DELETE], true);
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
        $this->area = $room;
        $this->token = $token;

        if ($this->decisionManager->decide($token, [SecurityData::getRoleGrrAdministrator()])) {
            return true;
        }

        switch ($attribute) {
            case self::NEW:
                return $this->canNew();
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


    private function canNew()
    {
        $area = $this->room->getArea();

        return $this->securityHelper->isAreaAdministrator($this->user, $area);
    }

    private function canAddEntry()
    {
        return $this->securityHelper->canAddEntry($this->user, $this->room);
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

        return $this->securityHelper->isRoomManager($this->user, $this->room);
    }

    private function canEdit()
    {
        return $this->securityHelper->isRoomAdministrator($this->user, $this->room);
    }

    private function canDelete()
    {
        return (bool)$this->canEdit();
    }
}
