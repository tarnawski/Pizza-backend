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
 * Class CompleteOrderController
 * @package ExternalApiBundle\Controller
 * @ApiDoc()
 */
class CompleteOrderController extends  BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Order';
    }

    /**
     * @ApiDoc(
     *  description="Create new Order",
     *  views = { "external" },
     *  parameters={
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Product description"},
     *      {"name"="customer", "dataType"="integer", "required"=true, "description"="ID Customer"},
     *  })
     * )
     * @param Request $request
     * @param Application $application
     * @ParamConverter("application", class="PizzaBundle\Entity\Application", options={"id" = "application_id"})
     * @return mixed
     *
     */
    public function createAction(Application $application, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create(new CompleteOrderType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $data = $form->getData();

        $itemFactory = $this->get('pizza.item.factory');

        $items = array();
        foreach ($data->item as $item) {
            /** @var Item $newItem */
            $items[] = $itemFactory->create($item);
        }


        $customerFactory = $this->get('pizza.customer.factory');

        /** @var Customer $customer */
        $customer = $customerFactory->create(
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->phone,
            $data->address
        );

        $orderFactory = $this->get('pizza.order.factory');
        /** @var Order $order */
        $order = $orderFactory->create(
            $data->description
        );

        $customer->setApplication($application);
        $order->setApplication($application);
        $order->setCustomer($customer);
        foreach($items as $item){
            $order->addItem($item);
            $em->persist($item);

        }

        $em->persist($customer);
        $em->persist($order);

        $em->flush();

      return $this->success($order, 'order', Response::HTTP_OK, array('Default', 'Order', 'Item', 'Product', 'ItemPrice'));
    }
}
