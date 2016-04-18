<?php

namespace PizzaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PizzaBundle\Entity\Application;

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

    /**
     * @param Application $application
     * @param string $email
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCustomerByEmail(Application $application, $email)
    {
        $builder = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.application = :application')
            ->andWhere('c.email LIKE :email')
            ->setParameters(array(
                'application' => $application->getId(),
                'email' => $email,
            ))
            ->getQuery()
            ->getOneOrNullResult();

        return $builder;
    }
}