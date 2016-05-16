<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\EmailNotification;
use PizzaBundle\Entity\Application;

class LoadEmailNotificationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const EMAIL_NOTIFICATION_NUMBER = 5;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::EMAIL_NOTIFICATION_NUMBER; $i++) {
            $emailNotification = new EmailNotification();
            $emailNotification->setName($faker->firstName);
            $emailNotification->setEmail($faker->email);
            $emailNotification->setActive($faker->boolean(80));
            /** @var Application $application */
            $application = $this->getReference('application');
            $emailNotification->setApplication($application);
            $manager->persist($emailNotification);
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
        return 11;
    }
}
