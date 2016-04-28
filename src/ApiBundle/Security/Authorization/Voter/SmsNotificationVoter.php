<?php

namespace ApiBundle\Security\Authorization\Voter;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\SmsNotification;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class SmsNotificationVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof SmsNotification) {
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

        /** @var SmsNotification $smsNotification */
        $smsNotification = $subject;

        switch($attribute) {
            case self::ACCESS:
                return $this->canAccess($smsNotification, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(SmsNotification $smsNotification, User $user)
    {
        /** @var Application $application */
        $application = $smsNotification->getApplication();
        $users = $application->getUsers();

        return $users->contains($user);
    }
}

