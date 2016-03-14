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
     * @var \Doctrine\ORM\EntityRepository
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        Order $order,
        $item
    )
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        /** @var Product $product */
        $product = $productRepository->find($item['product_id']);
        $priceRepository = $this->entityManager->getRepository(Price::class);
        /** @var Price $price */
        $price = $priceRepository->find($item['price_id']);
        
        $item = new Item();
        $item->setProduct($product);
        $item->setPrice($price);
        $item->setOrder($order);
        $item->setCount($item['count']);

        return $order;
    }
}
