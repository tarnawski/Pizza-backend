<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Product;
use PizzaBundle\Entity\Type;

class LoadTypeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $types = ['Type 1', 'Type 2', 'Type 3', 'Type 4', 'Type 5'];
        $i = 1;

            foreach($types as $value) {
                $type = new Type();
                $type->setName($value);
                /** @var Product $product */
                $product = $this->getReference(sprintf('product-%s', LoadProductData::PRODUCT_NUMBER - $i));
                $type->addProduct($product);
                $i++;
                $product = $this->getReference(sprintf('product-%s', LoadProductData::PRODUCT_NUMBER - $i));
                $type->addProduct($product);
                $type->setApplication($product->getApplication());
                $manager->persist($type);
                $i++;
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
