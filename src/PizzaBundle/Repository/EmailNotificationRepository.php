<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

class EmailNotificationRepository extends EntityRepository
{

    public function getActiveEmailsByApplication(Application $application, $active = true)
    {
        $builder = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.application = :application')
            ->andWhere('e.active = :active')
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