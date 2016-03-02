<?php

namespace OAuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

class User extends BaseUser
{
    const ROLE_API = 'ROLE_API';

    protected $id;

    public static $ROLES = array(
        self::ROLE_SUPER_ADMIN => self::ROLE_SUPER_ADMIN,
        self::ROLE_DEFAULT => self::ROLE_DEFAULT,
        self::ROLE_API => self::ROLE_API
    );
}
