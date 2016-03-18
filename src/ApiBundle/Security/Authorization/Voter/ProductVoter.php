<?php

namespace ApiBundle\Security\Authorization\Voter;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class ProductVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof Product) {
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

        /** @var Product $product */
        $product = $subject;

        switch($attribute) {
            case self::ACCESS:
                return $this->canAccess($product, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Product $product, User $user)
    {
        /** @var Application $application */
        $application = $product->getApplication();
        $users = $application->getUsers();

        return $users->contains($user);
    }
}

