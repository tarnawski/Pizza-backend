<?php

namespace OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Doctrine\ORM\Mapping as ORM;

class AuthCode extends BaseAuthCode
{
    protected $id;

    protected $client;

    protected $user;
}
