<?php

namespace OAuthBundle\Model;

use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\User;
use OAuth2\OAuth2;

class AccessTokenFactory
{
    /**
     * @var OAuth2
     */
    private $auth2;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $clientRepository;

    public function __construct(EntityManager $em, OAuth2 $auth2)
    {
        $this->clientRepository = $em->getRepository('OAuthBundle:Client');
        $this->auth2 = $auth2;
    }

    public function build(User $user, $data)
    {
        list($id, $randomId) = explode('_', $data->client_id);
        $client = $this->clientRepository->find($id);
        $token = $this->auth2->createAccessToken($client, $user);

        return $token;
    }
}
