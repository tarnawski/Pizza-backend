<?php

namespace PizzaBundle\Model;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Type;

class TypeFactory
{
    public function create(Application $application, $name)
    {
        $type = new Type();
        $type->setName($name);
        $type->setApplication($application);

        return $type;
    }
}
