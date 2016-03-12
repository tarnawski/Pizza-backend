<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\PromoCodeType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\PromoCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Application;

/**
 * Class PromoCodeController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class PromoCodeController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\PromoCode';
    }

    /**
     * @ApiDoc(
     *  description="Return all promo code belongs to application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $promoCodes = $application->getPromoCodes();

        if($promoCodes->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No promo code in application'));
        }

        return $this->success($promoCodes, 'promocode', Response::HTTP_OK, array('Default', 'PromoCode'));
    }

    /**
     * @ApiDoc(
     *  description="Return single promo code belongs to application",
     * )
     * @param PromoCode $promoCode
     * @return mixed
     * @ParamConverter("promoCode", class="PizzaBundle\Entity\PromoCode", options={"id" = "promocode_id"})
     */
    public function showAction(PromoCode $promoCode = null)
    {
        if ($promoCode == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }
        $application= $this->getApplication();
        if ($promoCode->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        return $this->success($promoCode, 'promocode', Response::HTTP_OK, array('Default', 'PromoCode'));
    }


    /**
     * @ApiDoc(
     *  description="Create new PromoCode",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of PromoCode"},
     *      {"name"="percent", "dataType"="boolean", "required"=true, "description"="If PromoCode is percent"},
     *      {"name"="overall", "dataType"="boolean", "required"=true, "description"="If PromoCode is overall"},
     *      {"name"="value", "dataType"="integer", "required"=true, "description"="Value of promo code"},
     *      {"name"="code", "dataType"="string", "required"=true, "description"="Is code of promo code"},
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

        $form = $this->get('form.factory')->create(new PromoCodeType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var PromoCode $promoCode */
        $promoCode = $form->getData();

        $promoCode->setApplication($application);

        $em = $this->getDoctrine()->getManager();
        $em->persist($promoCode);
        $em->flush();

        return $this->success($promoCode, 'promocode', Response::HTTP_OK, array('Default', 'PromoCode'));
    }

    /**
     * @ApiDoc(
     *  description="Update PromoCode",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of PromoCode"},
     *      {"name"="percent", "dataType"="boolean", "required"=true, "description"="If PromoCode is percent"},
     *      {"name"="overall", "dataType"="boolean", "required"=true, "description"="If PromoCode is overall"},
     *      {"name"="value", "dataType"="integer", "required"=true, "description"="Value of promo code"},
     *      {"name"="code", "dataType"="string", "required"=true, "description"="Is code of promo code"},
     *      {"name"="available", "dataType"="boolean", "required"=true, "description"="If Product is available"},
     *  })
     * )
     * @param Request $request
     * @param PromoCode $promoCode
     * @ParamConverter("promoCode", class="PizzaBundle\Entity\PromoCode", options={"id" = "promocode_id"})
     * @return mixed
     */
    public function updateAction(Request $request, PromoCode $promoCode = null)
    {
        if ($promoCode == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Promo code not found'));
        }
        $application = $this->getApplication();
        if ($promoCode->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Product not found'));
        }

        $form = $this->get('form.factory')->create(new PromoCodeType(), $promoCode);
        $formData = json_decode($request->getContent(), true);

        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($promoCode);
        $em->flush();

        return $this->success($promoCode, 'promocode', Response::HTTP_OK, array('Default', 'PromoCode'));
    }

    /**
     * @ApiDoc(
     *  description="Delete PromoCode",
     *)
     * @param PromoCode $promoCode
     * @return mixed|Response
     * @ParamConverter("promoCode", class="PizzaBundle\Entity\PromoCode", options={"id" = "promocode_id"})
     */
    public function deleteAction(PromoCode $promoCode = null)
    {
        if ($promoCode == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'PromoCode not found'));
        }
        $application = $this->getApplication();
        if ($promoCode->getApplication() != $application){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'PromoCode not found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($promoCode);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Product properly removed'));
    }
}
