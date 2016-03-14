<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\ProductType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;

/**
 * Class ProductController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class ProductController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Product';
    }

    /**
     * @ApiDoc(
     *  description="Return all Products belongs to Application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $products = $application->getProducts();

        if($products->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No Product in application'));
        }

        return $this->success($products, 'product', Response::HTTP_OK, array('Default', 'Product', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Product",
     * )
     * @param Product $product
     * @return mixed
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     */
    public function showAction(Product $product = null)
    {
        if ($product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product', 'Price'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Product",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Product name"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Product description"},
     *      {"name"="available", "dataType"="boolean", "required"=true, "description"="Availability Product"},
     *  })
     * )
     * @param Request $request
     * @return mixed
     */
    public function createAction(Request $request)
    {
        /** @var Application $application */
        $application = $this->getApplication();

        $form = $this->get('form.factory')->create(new ProductType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Product $product */
        $product = $form->getData();

        $product->setApplication($application);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product'));
    }

    /**
     * @ApiDoc(
     *  description="Update Product",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Product name"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Product description"},
     *      {"name"="available", "dataType"="boolean", "required"=true, "description"="Availability Product"},
     *  })
     * )
     * @param Request $request
     * @param Product $product
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @return mixed
     */
    public function updateAction(Request $request, Product $product = null)
    {
        if ($product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);

        $form = $this->get('form.factory')->create(new ProductType(), $product);
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Product",
     *)
     * @param Product $product
     * @return mixed|Response
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     */
    public function deleteAction(Product $product = null)
    {
        if ($product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Product properly removed'));
    }
}
