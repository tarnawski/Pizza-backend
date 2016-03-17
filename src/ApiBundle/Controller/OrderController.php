<?php

namespace ApiBundle\Controller;

use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use PizzaBundle\Entity\Order;
use PizzaBundle\Repository\OrderCriteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Hateoas\Configuration\Route;
use PizzaBundle\Repository\OrderRepository;
use PizzaBundle\Entity\Application;

/**
 * Class OrderController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class OrderController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Order';
    }

    /**
     * @ApiDoc(
     *  description="Return all Orders belongs to Application",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="realized", "dataType"="boolean", "required"=false, "description"="Filter by realized status"},
     *      {"name"="gte", "dataType"="datetime", "required"=false, "description"="Filter by date"},
     *      {"name"="lte", "dataType"="datetime", "required"=false, "description"="Filter by date"},
     *  }
     * )
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $application = $this->getApplication();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $orderCriteria = new OrderCriteria($application->getId());
        $orderCriteria->setGte($request->get('gte'));
        $orderCriteria->setLte($request->get('lte'));
        $orderCriteria->setRealized($request->get('realized'));

        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->get('pizza.order.repository');
        $qb = $orderRepository->getOrderWithCriteria($orderCriteria);

        $pagerFantaFactory = new PagerfantaFactory();
        $pagerFanta = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pagerFanta->setMaxPerPage($limit);
        $pagerFanta->setCurrentPage($page);


        $orders = $pagerFantaFactory->createRepresentation(
            $pagerFanta,
            new Route(
                $request->attributes->get('_route'),
                $params = array_merge($request->attributes->get('_route_params'), $request->query->all())
            )
        );

        return $this->success($orders, 'order', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Order",
     *  views = { "internal" }
     * )
     * @param Order $order
     * @return mixed
     * @ParamConverter("order", class="PizzaBundle\Entity\Order", options={"id" = "order_id"})
     */
    public function showAction(Order $order = null)
    {
        if ($order == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }
        $this->denyAccessUnlessGranted('access', $order);

        return $this->success($order, 'product', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Change status",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="realized", "dataType"="boolean", "required"=true, "description"="True if order realized"},
     *  })
     * )
     * @param Request $request
     * @param Order $order
     * @ParamConverter("order", class="PizzaBundle\Entity\Order", options={"id" = "order_id"})
     * @return mixed
     */
    public function changeStatusAction(Request $request, Order $order = null)
    {
        if ($order == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }
        $this->denyAccessUnlessGranted('access', $order);

        $formData = json_decode($request->getContent(), true);

        if(!isset($formData['realized'])){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Realized property not set'));
        }

        $order->setRealized($formData['realized']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->success($order, 'product', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice', 'Customer'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Order",
     *  views = { "internal" }
     *)
     * @param Order $order
     * @return mixed|Response
     * @ParamConverter("order", class="PizzaBundle\Entity\Order", options={"id" = "order_id"})
     */
    public function deleteAction(Order $order = null)
    {
        if ($order == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }
        $this->denyAccessUnlessGranted('access', $order);

        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Order properly removed'));
    }
}
