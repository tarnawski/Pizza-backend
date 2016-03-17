<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use OAuthBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Application;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $userAdmin = $this->createAdmin();
        $manager->persist($userAdmin);

        $user = new User();
        $user->setUsername('test');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_API');
        $user->setEmail('contact@test.eu');
        $user->setSuperAdmin(false);
        $user->setEnabled(true);
        $this->addReference('user', $user);
        $manager->persist($user);

        $manager->flush();
    }

    private function createAdmin()
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->addRole('ROLE_ADMIN')
            ->addRole('ROLE_SUPER_ADMIN');
        $userAdmin->setEmail('contact@clearcode.eu');
        $userAdmin->setSuperAdmin(true);
        $userAdmin->setEnabled(true);

        return $userAdmin;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
