<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    /**
     * @param OrderCriteria $criteria
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getOrderWithCriteria(OrderCriteria $criteria)
    {
        $builder = $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.application = :application')
            ->setParameter('application', $criteria->getApplicationId())
            ->orderBy('o.createDate', 'DESC');

        if($criteria->isRealized()){
            $builder
                ->andWhere('o.realized = :realized')
                ->setParameter('realized', $criteria->isRealized());
        }
        if($criteria->getGte()){
            $builder
                ->andWhere('o.createDate < :gte')
                ->setParameter('gte', $criteria->getGte());
        }
        if($criteria->getLte()){
            $builder
                ->andWhere('o.createDate > :lte')
                ->setParameter('lte', $criteria->getLte());
        }

        return $builder;
    }
}