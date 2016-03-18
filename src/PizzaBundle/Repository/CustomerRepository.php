<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository
{
    /**
     * @param CustomerCriteria $criteria
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCustomerWithCriteria(CustomerCriteria $criteria)
    {
        $builder = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.application = :application')
            ->andWhere('c.firstName LIKE :firstName')
            ->andWhere('c.lastName LIKE :lastName')
            ->andWhere('c.email LIKE :email')
            ->andWhere('c.phone LIKE :phone')
            ->andWhere('c.address LIKE :address')
            ->setParameters(array(
                'application' => $criteria->getApplicationId(),
                'firstName' => '%'.$criteria->getFirstName().'%',
                'lastName' => '%'.$criteria->getLastName().'%',
                'email' => '%'.$criteria->getEmail().'%',
                'phone' => '%'.$criteria->getPhone().'%',
                'address' => '%'.$criteria->getAddress().'%'
            ));

        return $builder;
    }
}