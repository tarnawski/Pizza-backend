<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const PRODUCT_NUMBER = 10;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::PRODUCT_NUMBER; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence(5,8));
            $product->setAvailable($faker->boolean());

            /** @var Application $application */
            $application = $this->getReference('application');
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
        return 4;
    }
}
