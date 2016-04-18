<?php
namespace PizzaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PromoCodeUnique extends Constraint
{

    public $message = 'Code must be unique';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'promo_code_unique_validate';
    }
}
