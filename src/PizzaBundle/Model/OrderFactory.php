<?php

namespace PizzaBundle\Model;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Order;

class OrderFactory
{
    public function create(
        $description
    ) {
        $order = new Order();
        $order->setDescription($description);
        $order->setRealized(false);
        $now = new \DateTime();
        $order->setCreateDate($now);

        return $order;
    }
}
