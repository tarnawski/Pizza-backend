<?php

namespace OAuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\PromoCode;

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
            $order->setDescription($faker->sentence(10));
            $order->setRealized($faker->boolean());
            $order->setTotalPrice($faker->randomFloat(2,10,40));

            if($faker->boolean(25)){
                $random = rand(0, LoadPromoCodeData::PROMO_CODES_NUMBER - 1);
                /** @var PromoCode $promoCode */
                $promoCode = $this->getReference(sprintf('promo_code-%s', $random));
                $order->setPromoCode($promoCode->getCode());
                $promoCode->isPercent() ? $type = 'percent' : $type = 'overall';
                $order->setPromoCodeType($type);
                $order->setPromoCodeValue($promoCode->getValue());
            }

            $random = rand(0, LoadCustomerData::CUSTOMER_NUMBER - 1);
            /** @var Customer $customer */
            $customer = $this->getReference(sprintf('customer-%s', $random));
            $order->setCustomerFirstName($customer->getFirstName());
            $order->setCustomerLastName($customer->getLastName());
            $order->setCustomerEmail($customer->getEmail());
            $order->setCustomerPhone($customer->getPhone());
            $order->setCustomerAddress($customer->getAddress());

            /** @var Application $application */
            $application = $this->getReference('application');
            $order->setApplication($application);
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
        return 9;
    }
}
