<?php

namespace App\Security;

use App\Entity\Area;
use App\Entity\User;
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
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';
    private $decisionManager;

    /**
     * @var User $user
     */
    private $user;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
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

        return in_array(
            $attribute,
            [self::INDEX, self::SHOW, self::EDIT, self::DELETE]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $volontaire, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $this->user = $user;

        if ($this->decisionManager->decide($token, [SecurityData::getRoleAdmin()])) {
            return true;
        }

        switch ($attribute) {
            case self::INDEX:
                return $this->canIndex();
            case self::SHOW:
                return $this->canView($volontaire, $token);
            case self::EDIT:
                return $this->canEdit($volontaire, $token);
            case self::DELETE:
                return $this->canDelete($volontaire, $token);
        }

        return false;
    }

    private function canIndex()
    {

    }

    /**
     * Voir dans l'admin
     * @param Area $volontaire
     * @param TokenInterface $token
     * @return bool
     */
    private function canView(Area $volontaire, TokenInterface $token)
    {
        if ($this->canEdit($volontaire, $token)) {
            return true;
        }

    }

    private function canEdit(Area $volontaire, TokenInterface $token)
    {
        $user = $token->getUser();

        return $user === $volontaire->getUser();
    }

    private function canDelete(Area $volontaire, TokenInterface $token)
    {
        if ($this->canEdit($volontaire, $token)) {
            return true;
        }

        return false;
    }
}
