<?php

namespace PizzaBundle\Notification\Strategies;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Order;

interface SendingStrategy
{
    /**
     * @param Application $application
     * @param Order $order
     * @return mixed
     */
    public function send(Application $application, Order $order);
}