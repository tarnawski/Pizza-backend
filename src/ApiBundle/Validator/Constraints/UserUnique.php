<?php
namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserUnique extends Constraint
{
    public $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }
    public $message = 'Username or email exist in system';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'user_validate';
    }
}
