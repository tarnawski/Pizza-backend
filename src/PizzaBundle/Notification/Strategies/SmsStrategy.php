<?php

namespace PizzaBundle\Notification\Strategies;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\SmsNotification;
use PizzaBundle\Repository\SmsNotificationRepository;

class SmsStrategy implements SendingStrategy
{
    const TOKEN = "wygenerowany_token";

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
            $params = array(
                'to' => '500000000',
                'from' => 'Info',
                'message' => "Hello world!",
            );

            $this->sms_send($params, self::TOKEN);
        }
    }

    private function sms_send($params, $token, $backup = false)
    {

        if ($backup == true) {
            $url = 'https://api2.smsapi.pl/sms.do';
        } else {
            $url = 'https://api.smsapi.pl/sms.do';
        }

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));

        $content = curl_exec($c);
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($http_status != 200 && $backup == false) {
            $backup = true;
            $this->sms_send($params, $token, $backup);
        }

        curl_close($c);

        return $content;
    }

    private function replaceSpecialChars($string)
    {
        return strtr($string, 'ĘÓĄŚŁŻŹĆŃęóąśłżźćń', 'EOASLZZCNeoaslzzcn');
    }

    private function maxString($string, $max)
    {
        return substr($string, 0, $max);
    }
}