<?php

namespace OAuthBundle\Model;

use OAuthBundle\Entity\User;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserFactory
{
    public function build($username, $email, $userId, $fieldName)
    {
        $user = $this->createUser($username, $email);
        $user->setPlainPassword(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($user, $fieldName, $userId);

        return $user;
    }

    public function buildAfterRegistration($username, $email, $password)
    {
        $user = $this->createUser($username, $email);
        $user->setPlainPassword($password);

        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @return User
     */
    private function createUser($username, $email)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles(['ROLE_API']);
        $user->setEnabled(true);

        return $user;
    }
}
