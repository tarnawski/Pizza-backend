<?php

namespace PizzaBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use PizzaBundle\Entity\PromoCode;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use OAuthBundle\Entity\User;

class PromoCodeUniqueValidator extends ConstraintValidator
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
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
        $promoCodeRepository = $this->entityManager->getRepository(PromoCode::class);

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $application = $user->getApplication();

        $result = $promoCodeRepository->getPromoCodeByCode($application ,$accessor->getValue($protocol, 'code'));

        if ($result != null) {
            return false;
        }

        return true;
    }
}
