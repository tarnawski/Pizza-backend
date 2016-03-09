<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Order;

class LoadOrderData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const ORDER_NUMBER = 200;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < LoadOrderData::ORDER_NUMBER; $i++) {
            $order = new Order();
            $order->setCreateDate($faker->dateTime);
            $order->setDescription($faker->sentence(50));
            $order->setRealized($faker->boolean());
            $random = rand(0, LoadCustomerData::CUSTOMER_NUMBER - 1);
            $customer = $this->getReference(sprintf('customer-%s', $random));
            $order->setCustomer($customer);
            $this->addReference(sprintf('order-%s', $i), $order);

            $manager->persist($order);
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
        return 10;
    }
}
