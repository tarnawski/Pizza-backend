<?php

namespace PizzaBundle\Model;

use Doctrine\ORM\EntityManager;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\Product;

class ItemFactory
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($item)
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        /** @var Product $product */
        $product = $productRepository->find($item['product_id']);
        $priceRepository = $this->entityManager->getRepository(Price::class);
        /** @var Price $price */
        $price = $priceRepository->find($item['price_id']);

        $newItem = new Item();
        $newItem->setProductName($product->getName());
        $newItem->setProductDescription($product->getDescription());
        $newItem->setProductType($price->getType());
        $newItem->setProductPrice($price->getValue());

        return $newItem;
    }
}
