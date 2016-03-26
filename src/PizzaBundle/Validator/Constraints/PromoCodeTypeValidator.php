<?php

namespace PizzaBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PromoCodeTypeValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        $count = 0;
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($constraint->properties['fields'] as $field) {
            if($accessor->getValue($protocol, $field['name'])){
                $count++;
            }
        }

        if ($count != 1) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}
