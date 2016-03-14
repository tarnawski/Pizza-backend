<?php

namespace PizzaBundle\Model;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Order;

class OrderFactory
{
    public function create(
        Application $application,
        Customer $customer,
        $description,
        $items
    ) {
        $order = new Order();
        $order->setApplication($application);
        $order->setCustomer($customer);
        $order->setDescription($description);
        $order->setRealized(false);
        $now = new \DateTime();
        $order->setCreateDate($now);

//        var_dump($items);exit;


        foreach ($items as $item){
            $newItem = new Item();
            $newItem->setProduct();
            $newItem->setPrice();
            $newItem->setOrder();
            $newItem->setCount();
        }


        return $order;
    }
}
