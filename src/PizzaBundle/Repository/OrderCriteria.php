<?php

namespace PizzaBundle\Repository;

class OrderCriteria
{
    /**
     * @var integer;
     */
    private $applicationId;

    /**
     * @var \DateTime
     */
    private $gte;

    /**
     * @var \DateTime
     */
    private $lte;

    public function __construct($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    /**
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return \DateTime
     */
    public function getGte()
    {
        return $this->gte;
    }

    /**
     * @param \DateTime $gte
     */
    public function setGte($gte)
    {
        $this->gte = $gte;
    }

    /**
     * @return \DateTime
     */
    public function getLte()
    {
        return $this->lte;
    }

    /**
     * @param \DateTime $lte
     */
    public function setLte($lte)
    {
        $this->lte = $lte;
    }

    /**
     * @return boolean
     */
    public function isRealized()
    {
        return $this->realized;
    }

    /**
     * @param boolean $realized
     */
    public function setRealized($realized)
    {
        $this->realized = $realized;
    }

    /**
     * @var boolean
     */
    private $realized;

}