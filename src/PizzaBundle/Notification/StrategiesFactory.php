<?php

namespace PizzaBundle\Notification;

use PizzaBundle\Notification\Strategies\SendingStrategy;

class StrategiesFactory
{

    private $emailStrategy;

    const EMAIL_STRATEGY = 'email';

    public function __construct(SendingStrategy $emailStrategy)
    {
        $this->emailStrategy = $emailStrategy;
    }

    public function get($method)
    {
        switch ($method) {
            case self::EMAIL_STRATEGY:
            default:
                return $this->emailStrategy;
        }
    }
}