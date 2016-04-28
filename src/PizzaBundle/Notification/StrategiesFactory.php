<?php

namespace PizzaBundle\Notification;

use PizzaBundle\Notification\Strategies\SendingStrategy;

class StrategiesFactory
{

    private $emailStrategy;
    private $smsStrategy;

    const EMAIL_STRATEGY = 'email';
    const SMS_STRATEGY = 'sms';

    public function __construct(
        SendingStrategy $emailStrategy,
        SendingStrategy $smsStrategy
    ) {
        $this->emailStrategy = $emailStrategy;
        $this->smsStrategy = $smsStrategy;
    }

    public function get($method)
    {
        switch ($method) {
            case self::EMAIL_STRATEGY:
                return $this->emailStrategy;
                break;
            case self::SMS_STRATEGY:
                return $this->smsStrategy;
                break;
            default:
                return $this->emailStrategy;
        }
    }
}