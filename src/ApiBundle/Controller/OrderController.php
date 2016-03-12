<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\OrderType;
use ApiBundle\Form\Type\ProductType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OAuthBundle\Entity\User;
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
     *  description="Return all orders belongs to application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $orders = $application->getOrders();

        if($orders->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No orders in application'));
        }

        return $this->success($orders, 'order', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice'));
    }

    /**
     * @ApiDoc(
     *  description="Return single order belongs to application",
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
        $application= $this->getApplication();
        if ($order->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }

        return $this->success($order, 'product', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice'));
    }

    /**
     * @ApiDoc(
     *  description="Change status",
     *  parameters={
     *      {"name"="realized", "dataType"="boolean", "required"=true, "description"="Is order realized"},
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
        $application = $this->getApplication();
        if ($order->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }

        $formData = json_decode($request->getContent(), true);

        if(!isset($formData['realized'])){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Realized property not set'));
        }

        $order->setRealized($formData['realized']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->success($order, 'product', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Order",
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
        $application = $this->getApplication();
        if ($order->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Order not found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Order properly removed'));
    }
}
