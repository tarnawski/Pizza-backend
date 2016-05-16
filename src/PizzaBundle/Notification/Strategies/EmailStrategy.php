<?php

namespace PizzaBundle\Notification\Strategies;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\EmailNotification;
use PizzaBundle\Entity\Order;
use PizzaBundle\Repository\EmailNotificationRepository;

class EmailStrategy implements SendingStrategy
{
    /**
     * @var EmailNotificationRepository
     */
    private $emailNotificationRepository;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $mailer;

    public function __construct(
        EmailNotificationRepository $emailNotificationRepository,
        \Twig_Environment $twig,
        $mailer
    ) {
        $this->emailNotificationRepository = $emailNotificationRepository;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function send(Application $application, $order)
    {
        $emailNotifications = $this->emailNotificationRepository->getActiveEmailsByApplication($application);

        /** @var EmailNotification $emailNotification */
        foreach ($emailNotifications as $emailNotification){
            $message = new \Swift_Message();
            $message->setSubject('Nowe zamówienie');
            $message->setFrom('tarnawski@go2.pl');
            $message->setTo($emailNotification->getEmail());
            $message->setBody(
                    $this->twig->render(
                        'Emails/notification.html.twig',
                        $order
                    ),
                    'text/html'
                );

           $this->mailer->send($message);
        }
    }
}