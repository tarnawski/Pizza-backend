<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Application;

class LoadApplicationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const APPLICATIONS_NUMBER = 50;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::APPLICATIONS_NUMBER; $i++) {
            $application = new Application();
            $application->setName($faker->word);
            $application->setDescription($faker->sentence(12));
            $application->setHomepage($faker->url);

            $this->addReference(sprintf('application-%s', $i), $application);

            $manager->persist($application);
        }

        $manager->flush();
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
