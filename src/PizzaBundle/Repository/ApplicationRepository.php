<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

class ApplicationRepository extends EntityRepository
{

    public function getApplicationWithActiveProducts(Application $application)
    {
        $result = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.types', 't')
            ->addSelect('t')
            ->leftJoin('t.products', 'p', 'WITH', 'p.available = true')
            ->addSelect('p')
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result;
    }
}