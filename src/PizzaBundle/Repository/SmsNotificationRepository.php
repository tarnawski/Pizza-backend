<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

class SmsNotificationRepository extends EntityRepository
{

    public function getActiveNumberByApplication(Application $application, $active = true)
    {
        $builder = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.application = :application')
            ->andWhere('n.active = :active')
            ->setParameters(array(
                'application' => $application,
                'active' => $active
            ))
            ->getQuery()
            ->getResult()
        ;

        return $builder;
    }
}