<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Customer;

class LoadCustomerData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const CUSTOMER_NUMBER = 50;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::CUSTOMER_NUMBER; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName);
            $customer->setLastName($faker->lastName);
            $customer->setAddress($faker->address);
            $customer->setEmail($faker->email);
            $customer->setPhone($faker->phoneNumber);
            $random = rand(0, LoadApplicationData::APPLICATIONS_NUMBER - 1);
            $application = $this->getReference(sprintf('application-%s', $random));
            $customer->setApplication($application);
            $this->addReference(sprintf('customer-%s', $i), $customer);
            $manager->persist($customer);
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
        return 5;
    }
}
