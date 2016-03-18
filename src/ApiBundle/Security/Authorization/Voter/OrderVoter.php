<?php

namespace ApiBundle\Security\Authorization\Voter;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class OrderVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof Order) {
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

        /** @var Order $order */
        $order = $subject;

        switch($attribute) {
            case self::ACCESS:
                return $this->canAccess($order, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Order $order, User $user)
    {
        /** @var Application $application */
        $application = $order->getApplication();
        $users = $application->getUsers();

        return $users->contains($user);
    }
}

