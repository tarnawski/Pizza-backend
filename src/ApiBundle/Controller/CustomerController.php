<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\CustomerType;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Repository\CustomerCriteria;
use PizzaBundle\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Hateoas\Configuration\Route;
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
     *  description="Return all Customers belongs to Application",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="first_name", "dataType"="string", "required"=false, "description"="Filter by first name"},
     *      {"name"="last_name", "dataType"="string", "required"=false, "description"="Filter by last name"},
     *      {"name"="email", "dataType"="string", "required"=false, "description"="Filter by email"},
     *      {"name"="phone", "dataType"="string", "required"=false, "description"="Filter by phone"},
     *      {"name"="address", "dataType"="string", "required"=false, "description"="Filter by address"},
     *  }
     * )
     * )
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $application = $this->getApplication();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $customerCriteria = new CustomerCriteria($application->getId());
        $customerCriteria->setFirstName($request->get('first_name'));
        $customerCriteria->setLastName($request->get('last_name'));
        $customerCriteria->setPhone($request->get('email'));
        $customerCriteria->setAddress($request->get('phone'));
        $customerCriteria->setEmail($request->get('address'));

        /** @var CustomerRepository $customerRepository */
        $customerRepository = $this->get('pizza.customer.repository');
        $qb = $customerRepository->getCustomerWithCriteria($customerCriteria);

        $pagerFantaFactory = new PagerfantaFactory();
        $pagerFanta = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pagerFanta->setMaxPerPage($limit);
        $pagerFanta->setCurrentPage($page);


        $customers = $pagerFantaFactory->createRepresentation(
            $pagerFanta,
            new Route(
                $request->attributes->get('_route'),
                $params = array_merge($request->attributes->get('_route_params'), $request->query->all())
            )
        );

        return $this->success($customers, 'customer', Response::HTTP_OK, array('Default', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Customer",
     *  views = { "internal" }
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
     *  description="Update Customer",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="first_name", "dataType"="string", "required"=true, "description"="First name"},
     *      {"name"="last_name", "dataType"="string", "required"=true, "description"="Last name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *      {"name"="phone", "dataType"="string", "required"=true, "description"="Phone"},
     *      {"name"="address", "dataType"="string", "required"=true, "description"="Address"},
     *  })
     * )
     * @param Request $request
     * @param Customer $customer
     * @ParamConverter("customer", class="PizzaBundle\Entity\Customer", options={"id" = "customer_id"})
     * @return mixed
     */
    public function updateAction(Request $request, Customer $customer = null)
    {
        if ($customer == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $customer);

        $form = $this->get('form.factory')->create(new CustomerType(), $customer);
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();

        return $this->success($customer, 'customer', Response::HTTP_OK, array('Default', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Customer",
     *  views = { "internal" }
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
