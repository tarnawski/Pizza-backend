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
use OAuthBundle\Entity\User;
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
     *  description="Return all products belongs to application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $products = $application->getProducts();

        if($products->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No product in application'));
        }

        return $this->success($products, 'product', Response::HTTP_OK, array('Default', 'Product'));
    }

    /**
     * @ApiDoc(
     *  description="Return single product belongs to application",
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
        $application= $this->getApplication();
        if ($product->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        return $this->success($product, 'product', Response::HTTP_OK, array('Default', 'Product'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Product",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of Type Product"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Description of Type Product"},
     *      {"name"="available", "dataType"="boolean", "required"=true, "description"="If Product is available"},
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
}
