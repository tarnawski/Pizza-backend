<?php

namespace PizzaBundle\Model;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Order;

class OrderFactory
{
    public function create(
        $description,
        $first_name,
        $last_name,
        $email,
        $phone,
        $address
    ) {
        $order = new Order();
        $order->setDescription($description);
        $order->setRealized(false);
        $now = new \DateTime();
        $order->setCreateDate($now);
        $order->setCustomerFirstName($first_name);
        $order->setCustomerLastName($last_name);
        $order->setCustomerEmail($email);
        $order->setCustomerPhone($phone);
        $order->setCustomerAddress($address);

        return $order;
    }
}
