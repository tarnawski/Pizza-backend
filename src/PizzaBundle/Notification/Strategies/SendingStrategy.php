<?php

namespace PizzaBundle\Notification\Strategies;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Order;

interface SendingStrategy
{
    /**
     * @param Application $application
     * @param array $order
     * @return mixed
     */
    public function send(Application $application, $order);
}