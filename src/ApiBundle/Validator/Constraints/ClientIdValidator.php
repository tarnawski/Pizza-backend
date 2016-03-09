<?php
namespace ApiBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\Client;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ClientIdValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($protocol, Constraint $constraint)
    {
        list($id, $randomId) = explode('_', $protocol->client_id);
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        if ($client == null || $client->getRandomId()!=$randomId || !$client->checkSecret($protocol->client_secret)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
