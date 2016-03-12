<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;

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
     *  description="Return all customers belongs to application",
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
     *  description="Return single customer belongs to application",
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
        $application= $this->getApplication();
        if ($customer->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Customer not found'));
        }

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
        $application = $this->getApplication();
        if ($customer->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Customer not found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Customer properly removed'));
    }
}
