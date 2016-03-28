<?php

namespace PizzaBundle\Model;

use Doctrine\ORM\EntityManager;
use PizzaBundle\Entity\PromoCode;

class PromoCodeBusiness
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $price
     * @param PromoCode $promoCode
     * @return mixed
     */
    public function calculatePriceWithPromoCode($price, PromoCode $promoCode)
    {
        if (!$promoCode->isAvailable()) {
            return $price;
        } else {
            if ($promoCode->isOverall()) {
                return $price - $promoCode->getValue();
            } elseif ($promoCode->isPercent()) {
                $discount = $price/100 * $promoCode->getValue();
                return $price - $discount;
            }
        }

        return $price;
    }
}