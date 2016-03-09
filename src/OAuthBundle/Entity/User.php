<?php

namespace OAuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use PizzaBundle\Entity\Application;

class User extends BaseUser
{
    const ROLE_API = 'ROLE_API';

    protected $id;

    /**
     * @var Application
     */
    private $application;

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    public static $ROLES = array(
        self::ROLE_SUPER_ADMIN => self::ROLE_SUPER_ADMIN,
        self::ROLE_DEFAULT => self::ROLE_DEFAULT,
        self::ROLE_API => self::ROLE_API
    );
}
