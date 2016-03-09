<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Item;

class LoadItemData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const ITEMS_NUMBER = 500;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::ITEMS_NUMBER; $i++) {
            $item = new Item();
            $item->setCount($faker->numberBetween(1,3));
            $random = rand(0, LoadProductData::PRODUCT_NUMBER - 1);
            $product = $this->getReference(sprintf('product-%s', $random));
            $item->setProduct($product);
            $random = rand(0, LoadOrderData::ORDER_NUMBER - 1);
            $order = $this->getReference(sprintf('order-%s', $random));
            $item->setOrder($order);
            $manager->persist($item);
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
