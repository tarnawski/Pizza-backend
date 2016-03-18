<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\PromoCode;
use PizzaBundle\Entity\Application;

class LoadPromoCodeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const PROMO_CODES_NUMBER = 5;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < self::PROMO_CODES_NUMBER; $i++) {
            $promoCode = new PromoCode();
            $promoCode->setName($faker->word);
            $promoCode->setValue($faker->numberBetween(1,12));
            $promoCode->setCode($faker->word);
            $promoCode->setAvailable($faker->boolean());
            $type = $faker->boolean();
            $promoCode->setOverall($type);
            $promoCode->setPercent(!$type);
            /** @var Application $application */
            $application = $this->getReference('application');
            $promoCode->setApplication($application);
            $manager->persist($promoCode);
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
