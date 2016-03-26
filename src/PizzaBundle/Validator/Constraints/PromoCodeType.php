<?php
namespace PizzaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PromoCodeType extends Constraint
{

    public $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }
    public $message = 'One of type must be set';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'promo_code_type_validate';
    }
}
