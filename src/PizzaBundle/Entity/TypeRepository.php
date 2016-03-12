<?php

namespace PizzaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TypeRepository
 */
class TypeRepository extends EntityRepository
{
    /*
     * @param Application $application
     * @return Type $response
     */
    public function getTypesByApplication(Application $application)
    {
        $types = $this->createQueryBuilder('t')
            ->select('t')
            ->leftJoin('Product', 'p', 't.product_id = p.id')
            ->leftJoin('Application', 'a', 'a.id = p.application_id')
            ->where('a.id = :idApplication')
            ->setParameters(array('idApplication' => $application->getId()))
            ->getQuery()
            ->getResult();

        return $types;
    }
}
