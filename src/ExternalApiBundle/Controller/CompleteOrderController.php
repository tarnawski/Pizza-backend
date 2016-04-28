<?php

namespace ExternalApiBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use ExternalApiBundle\Form\Type\CompleteOrderType;
use ExternalApiBundle\Model\CompleteOrder;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\PromoCode;
use PizzaBundle\Model\PromoCodeBusiness;
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

        $orderFactory = $this->get('pizza.order.factory');
        /** @var Order $order */
        $order = $orderFactory->create(
            $data->description,
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->phone,
            $data->address
        );

        $itemFactory = $this->get('pizza.item.factory');

        $items = array();
        foreach ($data->item as $item) {
            $items[] = $itemFactory->create($item);
        }


        $order->setApplication($application);
        foreach($items as $item){
            $order->addItem($item);
            $em->persist($item);
        }

        $totalPrice = 0;
        foreach ($order->getItems() as $item){
            $totalPrice += $item->getProductPrice();;
        }

        if($data->promoCode){
            $promoCodeRepository = $this->getRepository(PromoCode::class);
            /** @var PromoCode $promoCode */
            $promoCode = $promoCodeRepository->getPromoCodeByCode($application, $data->promoCode);
            if($promoCode){
                $order->setPromoCode($promoCode->getCode());
                $order->setPromoCodeValue($promoCode->getValue());
                $promoCode->isPercent() ? $type = 'percent' : $type = 'overall';
                $order->setPromoCodeType($type);
                /** @var PromoCodeBusiness $promoCodeBusiness */
                $promoCodeBusiness = $this->get('pizza.promo_code.business');
                $totalPrice = $promoCodeBusiness->calculatePriceWithPromoCode($totalPrice, $promoCode);
            }
        }

        $order->setTotalPrice($totalPrice);

        // Create customer if not exist
        $customerRepository = $this->getRepository(Customer::class);
        $customer = $customerRepository->getCustomerByEmail($application, $data->email);

        if($customer == null) {
            $customerFactory = $this->get('pizza.customer.factory');
            /** @var Customer $customer */
            $customer = $customerFactory->create(
                $data->first_name,
                $data->last_name,
                $data->email,
                $data->phone,
                $data->address
            );
            $customer->setApplication($application);
            $em->persist($customer);
        }

        $em->persist($order);
        $em->flush();

        $arrayOrder = $this->orderToArray($order);

        //Send notification: email | sms
        $strategy = $this->get('pizza.notification.strategy.factory')->get('email');
        $strategy->send($application, $arrayOrder);

        return JsonResponse::create(array('status' => 'Success', 'message' => 'Order saved'));
    }

    /**
     * @param Order $order
     * @return array
     */
    private function orderToArray(Order $order)
    {
        $arrayOrder = [
            "totalPrice" => $order->getTotalPrice(),
            'date' => $order->getCreateDate(),
            'customerFirstName' => $order->getCustomerFirstName(),
            'customerLastName' => $order->getCustomerLastName(),
            'customerEmail' => $order->getCustomerEmail(),
            'customerPhone' => $order->getCustomerPhone(),
            'customerAddress' => $order->getCustomerAddress(),
            'description' => $order->getDescription(),
        ];
        if($order->getPromoCode() != null){
            $promoCode =[
                'promoCode' => $order->getPromoCode(),
                'promoCodeType' => $order->getPromoCodeType(),
                'promoCodeValue' => $order->getpromoCodeValue()
            ];
            $arrayOrder = array_merge($arrayOrder, $promoCode);
        }

        $arrayItem = [];
        /** @var Item $item */
        foreach($order->getItems() as $item){
          $arrayItem[] = [
              'productName' => $item->getProductName(),
              'productDescription' => $item->getProductDescription(),
              'productType' => $item->getProductType(),
              'productPrice' => $item->getProductPrice()
          ];
        }
        $arrayOrder = array_merge($arrayOrder, ['items' => $arrayItem]);

        return $arrayOrder;
    }
}
