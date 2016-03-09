<?php
namespace ApiBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\Client;
use OAuthBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserUniqueValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($protocol, Constraint $constraint)
    {

        if ($this->isUnique($constraint, $protocol) == false) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    public function isUnique(Constraint $constraint, $protocol)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $userRepository = $this->entityManager->getRepository(User::class);
        foreach ($constraint->properties['fields'] as $field) {
            $result = $userRepository->findOneBy(array($field['mapping'] => $accessor->getValue($protocol, $field['name'])));
            if ($result != null) {
                return false;
            }
        }

        return true;
    }
}