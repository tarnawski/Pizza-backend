<?php

namespace ApiBundle\Security\Authorization\Voter;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class ProductVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
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
            case self::VIEW:
                return $this->canView($product, $user);
            case self::EDIT:
                return $this->canEdit($product, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Product $product, User $user)
    {
        /** @var Application $application */
        $application = $product->getApplication();
        $users = $application->getUsers();

        return $users->contains($user);
    }

    private function canEdit(Product $product, User $user)
    {
        /** @var Application $application */
        $application = $product->getApplication();
        $users = $application->getUsers();

        return $users->contains($user);
    }
}
