<?php

namespace PizzaBundle\Model;

use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;

class CustomerFactory
{
    public function create(
        $firstName,
        $lastName,
        $email,
        $phone,
        $address
    ) {
        $customer = new Customer();
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $customer->setEmail($email);
        $customer->setPhone($phone);
        $customer->setAddress($address);

        return $customer;
    }
}
