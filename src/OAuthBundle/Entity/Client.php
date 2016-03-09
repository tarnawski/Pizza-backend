<?php

namespace OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

class Client extends BaseClient
{
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}
