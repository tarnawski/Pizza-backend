<?php

namespace ApiBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Price;

class PriceVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Price) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Price $price */
        $price = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($price, $user);
            case self::EDIT:
                return $this->canEdit($price, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Price $price, User $user)
    {
        if ($this->canEdit($price, $user)) {
            return true;
        }

        return !$price->isPrivate();
    }

    private function canEdit(Price $price, User $user)
    {
        return $user === $price->getOwner();
    }
}
