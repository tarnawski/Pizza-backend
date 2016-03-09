<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Price;

class LoadPriceData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $types = ['Small', 'Average', 'Large', 'Mega'];

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < LoadProductData::PRODUCT_NUMBER; $i++) {
            foreach($types as $type){
                $price = new Price();
                $price->setType($type);
                $price->setValue($faker->randomFloat(2,10,40));

                $product = $this->getReference(sprintf('product-%s', $i));
                $price->setProduct($product);
                $manager->persist($price);
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
        return 7;
    }
}
