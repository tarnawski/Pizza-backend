<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\EmailNotification;
use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\SmsNotification;

class LoadSmsNotificationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const SMS_NOTIFICATION_NUMBER = 2;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::SMS_NOTIFICATION_NUMBER; $i++) {
            $smsNotification = new SmsNotification();
            $smsNotification->setName($faker->firstName);
            $smsNotification->setNumber($faker->phoneNumber);
            $smsNotification->setActive($faker->boolean(80));
            /** @var Application $application */
            $application = $this->getReference('application');
            $smsNotification->setApplication($application);
            $manager->persist($smsNotification);
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
        return 12;
    }
}
