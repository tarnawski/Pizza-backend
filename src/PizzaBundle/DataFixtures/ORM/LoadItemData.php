<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Product;

class LoadItemData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const ITEMS_IN_ORDER = 4;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < LoadOrderData::ORDER_NUMBER; $i++) {
            for ($j = 0; $j < self::ITEMS_IN_ORDER; $j++) {
                $item = new Item();
                $item->setCount($faker->numberBetween(1, 3));
                $random = rand(0, LoadProductData::PRODUCT_NUMBER - 1);
                /** @var Product $product */
                $product = $this->getReference(sprintf('product-%s', $random));
                $item->setProduct($product);
                /** @var Order $order */
                $order = $this->getReference(sprintf('order-%s', $i));
                $item->setOrder($order);
                $item->setPrice($product->getPrices()->first());
                $manager->persist($item);
            }
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
