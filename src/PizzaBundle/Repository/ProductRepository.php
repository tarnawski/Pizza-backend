<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

class ProductRepository extends EntityRepository
{
    /**
     * @param Application $application
     * @param boolean $available
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getProductByAvailable(Application $application, $available = true)
    {
        $builder = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.application = :application')
            ->andWhere('p.available = :available')
            ->setParameters(array(
                'application' => $application,
                'available' => $available,
            ))
            ->getQuery()
            ->getResult();

        return $builder;
    }
}