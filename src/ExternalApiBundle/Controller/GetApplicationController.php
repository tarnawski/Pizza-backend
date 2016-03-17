<?php

namespace ExternalApiBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use ExternalApiBundle\Form\Type\CompleteOrderType;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Item;

/**
 * Class GetApplicationController
 * @package ExternalApiBundle\Controller
 * @ApiDoc()
 */
class GetApplicationController extends  BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Application';
    }

    /**
     * @ApiDoc(
     *  description="Return application",
     *  views = { "external" }
     * )
     * @param Application $application
     * @return mixed
     * @ParamConverter("application", class="PizzaBundle\Entity\Application", options={"id" = "application_id"})
     */
    public function showAction(Application $application)
    {
         return $this->success($application, 'application', Response::HTTP_OK, array('Default', 'External'));
    }
}
