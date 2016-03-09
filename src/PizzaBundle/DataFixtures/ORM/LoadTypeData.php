<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Type;

class LoadTypeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $types = ['Type 1', 'Type 2', 'Type 3', 'Type 4'];

        for ($i = 0; $i < LoadProductData::PRODUCT_NUMBER; $i++) {
            $type = new Type();
            $key = array_rand($types);
            $type->setName($types[$key]);
            $product = $this->getReference(sprintf('product-%s', $i));
            $type->addProduct($product);
            $manager->persist($type);
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
        return 8;
    }
}
