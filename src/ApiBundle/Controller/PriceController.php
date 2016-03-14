<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\PriceType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PriceController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class PriceController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Price';
    }

    /**
     * @ApiDoc(
     *  description="Return all Price belongs to Product",
     * )
     * @param Product $product
     * @return mixed
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     */
    public function indexAction(Product $product = null)
    {
        if($product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Not found'));
        }
        $prices = $product->getPrices();
        $this->denyAccessUnlessGranted('access', $prices->first());

        return $this->success($prices, 'price', Response::HTTP_OK, array('Default', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Price",
     * )
     * @param Price $price
     * @param  Product $product
     * @return mixed
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("price", class="PizzaBundle\Entity\Price", options={"id" = "price_id"})
     */
    public function showAction(Product $product = null, Price $price = null)
    {
        if($product == null || $price == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Not found'));
        }
        $this->denyAccessUnlessGranted('access', $price);

        return $this->success($price, 'price', Response::HTTP_OK, array('Default', 'Price'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Price",
     *  parameters={
     *      {"name"="type", "dataType"="string", "required"=true, "description"="Product type"},
     *      {"name"="value", "dataType"="float", "required"=true, "description"="Product price"},
     *  })
     * )
     * @param Product $product
     * @param Request $request
     * @return mixed
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     */
    public function createAction(Request $request, Product $product = null)
    {
        if($product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Not found'));
        }
        $application= $this->getApplication();
        if ($product->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        $form = $this->get('form.factory')->create(new PriceType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Price $price */
        $price = $form->getData();

        $price->setProduct($product);

        $em = $this->getDoctrine()->getManager();
        $em->persist($price);
        $em->flush();

        return $this->success($price, 'price', Response::HTTP_OK, array('Default', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Create new Price",
     *  parameters={
     *      {"name"="type", "dataType"="string", "required"=true, "description"="Product type"},
     *      {"name"="value", "dataType"="float", "required"=true, "description"="Product price"},
     *  })
     * )
     * @param Request $request
     * @param Product $product
     * @param Price $price
     * @return mixed|Response
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("price", class="PizzaBundle\Entity\Price", options={"id" = "price_id"})
     */
    public function updateAction(
        Request $request,
        Product $product,
        Price $price
    ) {

        if($product == null || $price == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Not found'));
        }
        $this->denyAccessUnlessGranted('access', $price);

        $form = $this->get('form.factory')->create(new PriceType(), $price);
        $formData = json_decode($request->getContent(), true);

        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($price);
        $em->flush();

        return $this->success($price, 'price', Response::HTTP_OK, array('Default', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Price",
     *)
     * @param Product $product
     * @param Price $price
     * @return mixed|Response
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("price", class="PizzaBundle\Entity\Price", options={"id" = "price_id"})
     */
    public function deleteAction(Product $product = null, Price $price = null)
    {
        if($product == null || $price == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Not found'));
        }
        $this->denyAccessUnlessGranted('access', $price);

        $em = $this->getDoctrine()->getManager();
        $em->remove($price);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Type properly removed'));
    }
}
