<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use OAuthBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const USERS_NUMBER = 50;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');

        $userAdmin = $this->createAdmin();
        $manager->persist($userAdmin);

        for ($i = 0; $i < LoadApplicationData::APPLICATIONS_NUMBER; $i++) {
            $user = $this->createUser($faker->unique()->firstName, $faker->unique()->email);
            $this->addReference(sprintf('user-%s', $i), $user);
            $application = $this->getReference(sprintf('application-%s', $i));
            $user->setApplication($application);
            $manager->persist($user);
        }

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
        $random = rand(0, LoadApplicationData::APPLICATIONS_NUMBER - 1);
        $application = $this->getReference(sprintf('application-%s', $random));
        $userAdmin->setApplication($application);
        return $userAdmin;
    }

    private function createUser($userName, $email)
    {
        $faker = Factory::create('pl_PL');
        $user = new User();
        $user->setUsername($userName);
        $user->setEmail($email);
        $user->setPlainPassword($faker->password);
        $user->setRoles(['ROLE_API']);
        $user->setEnabled(true);

        return $user;
    }
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
