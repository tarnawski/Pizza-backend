<?php

namespace OAuthBundle\DataFixtures\ORM;

use OAuthBundle\Entity\Client;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadClientData implements FixtureInterface, ContainerAwareInterface
{
    const NUMBER_OF_CLIENTS = 20;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $androidClient = new Client();
        //For android app
        $androidClient->setRandomId('69xzvcmvpscooswgk8kc8kg08088c8ws0swso0cgogog80kwoo');
        $androidClient->setSecret('3qm0j3k82dog4ss8kokks4k0ow4so8cso0scoscck0ok0wwg84');
        $androidClient->setRedirectUris(array('http://www.example.com'));
        $androidClient->setAllowedGrantTypes(array('token', 'password', 'refresh_token'));
        $manager->persist($androidClient);

        //For backend
        $backendClient = new Client();
        $backendClient->setRandomId('2db5eckeoce8os0kg0k8kg0w4ccskc008wg4g0og84wg0o8cs8');
        $backendClient->setSecret('4ruoedqji1icg44ss44k40w4goog8884ggsgskg4g8g8ok00s0');
        $backendClient->setRedirectUris(array('http://www.example.com'));
        $backendClient->setAllowedGrantTypes(array('token', 'password', 'refresh_token'));
        $manager->persist($backendClient);

        $manager->flush();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function getContainer()
    {
        return $this->container;
    }
}
