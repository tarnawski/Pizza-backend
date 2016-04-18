<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

class PromoCodeRepository extends EntityRepository
{

    public function getPromoCodeByCode(Application $application, $code)
    {
        $builder = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.application = :application')
            ->andWhere('p.code = :code')
            ->setParameters(array(
                'application' => $application,
                'code' => $code
            ))
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $builder;
    }
}