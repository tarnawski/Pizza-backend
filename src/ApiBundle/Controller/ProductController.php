<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\ProductType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Product;
use PizzaBundle\Entity\Type;
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
     *  views = { "internal" }
     * )
     * @param Type $type
     * @return mixed
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function indexAction(Type $type = null)
    {
        $application = $this->getApplication();
        if($application == null || $type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'No application found'));
        }

        $products = $type->getProducts();


        return $this->success($products, 'product', Response::HTTP_OK, array('Default', 'Product', 'Price'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Product",
     *  views = { "internal" }
     * )
     * @param Type $type
     * @param Product $product
     * @return mixed
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function showAction(Type $type = null, Product $product = null)
    {
        if ($type == null || $product == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);

        if(!$type->getProducts()->contains($product)){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product', 'Price'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Product",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="realized", "dataType"="boolean", "required"=true, "description"="Realized Order"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Description Order"},
     *  })
     * )
     * @param Request $request
     * @param Type $type
     * @return mixed
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function createAction(Request $request, Type $type = null)
    {
        /** @var Application $application */
        $application = $this->getApplication();
        if($application == null || $type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'No application found'));
        }
        if(!$application->getTypes()->contains($type)){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Type not found'));
        }
        
        $form = $this->get('form.factory')->create(new ProductType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Product $product */
        $product = $form->getData();

        $product->setApplication($application);
        $product->setType($type);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product'));
    }

    /**
     * @ApiDoc(
     *  description="Update Product",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Product name"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Product description"},
     *      {"name"="available", "dataType"="boolean", "required"=true, "description"="Availability Product"},
     *  })
     * )
     * @param Request $request
     * @param Product $product
     * @param Type $type
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     * @return mixed
     */
    public function updateAction(Request $request, Product $product = null, Type $type = null)
    {
        if ($product == null || $type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);

        if(!$type->getProducts()->contains($product)){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

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
     *  views = { "internal" }
     *)
     * @param Product $product
     * @param Type $type
     * @return mixed|Response
     * @ParamConverter("product", class="PizzaBundle\Entity\Product", options={"id" = "product_id"})
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function deleteAction(Product $product = null, Type $type = null)
    {
        if ($product == null || $type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $this->denyAccessUnlessGranted('access', $product);
        if(!$type->getProducts()->contains($product)){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Product properly removed'));
    }
}
