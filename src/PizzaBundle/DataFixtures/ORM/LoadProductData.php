<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const PRODUCT_NUMBER = 50;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $arrayOfType = ['S', 'M', 'XL', 'XXL'];

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::PRODUCT_NUMBER; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence(5,15));
            $product->setAvailable($faker->boolean());

            $random = rand(0, LoadApplicationData::APPLICATIONS_NUMBER - 1);
            $application = $this->getReference(sprintf('application-%s', $random));
            $product->setApplication($application);

            $this->addReference(sprintf('product-%s', $i), $product);

            $manager->persist($product);
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
        return 6;
    }
}
