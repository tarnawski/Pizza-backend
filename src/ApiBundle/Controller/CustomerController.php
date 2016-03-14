<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CustomerController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class CustomerController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Customer';
    }

    /**
     * @ApiDoc(
     *  description="Return all Customers belongs to Application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $customers = $application->getCustomers();

        if($customers->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No customers in application'));
        }

        return $this->success($customers, 'customer', Response::HTTP_OK, array('Default', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Customer",
     * )
     * @param Customer $customer
     * @return mixed
     * @ParamConverter("customer", class="PizzaBundle\Entity\Customer", options={"id" = "customer_id"})
     */
    public function showAction(Customer $customer = null)
    {
        if ($customer == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Customer not found'));
        }
        $this->denyAccessUnlessGranted('access', $customer);

        return $this->success($customer, 'customer', Response::HTTP_OK, array('Default', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Customer",
     *)
     * @param Customer $customer
     * @return mixed|Response
     * @ParamConverter("customer", class="PizzaBundle\Entity\Customer", options={"id" = "customer_id"})
     */
    public function deleteAction(Customer $customer = null)
    {
        if ($customer == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Customer not found'));
        }
        $this->denyAccessUnlessGranted('access', $customer);

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Customer properly removed'));
    }
}
