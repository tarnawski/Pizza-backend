<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Application;

class LoadApplicationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');
        $application = new Application();
        $application->setName($faker->word);
        $application->setDescription($faker->sentence(12));
        $application->setHomepage($faker->url);
        $application->setCreateDate($faker->dateTime);
        $application->setDemo($faker->boolean());
        /** @var User $user */
        $user = $this->getReference('user');
        $application->addUser($user);

        $this->setReference('application', $application);
        $manager->persist($application);


        $manager->flush();
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
