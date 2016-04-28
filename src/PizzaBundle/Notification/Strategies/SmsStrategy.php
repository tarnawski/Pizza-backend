<?php

namespace PizzaBundle\Notification\Strategies;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\SmsNotification;
use PizzaBundle\Repository\SmsNotificationRepository;

class SmsStrategy implements SendingStrategy
{
    /**
     * @var SmsNotificationRepository
     */
    private $smsNotificationRepository;

    public function __construct(SmsNotificationRepository $smsNotificationRepository)
    {
        $this->smsNotificationRepository = $smsNotificationRepository;
    }

    public function send(Application $application, $order)
    {
        $smsNotifications = $this->smsNotificationRepository->getActiveNumberByApplication($application);

        /** @var SmsNotification $smsNotification */
        foreach ($smsNotifications as $smsNotification){

        }
    }
}