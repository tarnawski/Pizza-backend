<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\EmailNotificationType;
use ApiBundle\Form\Type\PromoCodeType;
use ApiBundle\Form\Type\SmsNotificationType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\EmailNotification;
use PizzaBundle\Entity\PromoCode;
use PizzaBundle\Entity\SmsNotification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;

/**
 * Class SmsNotificationController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class SmsNotificationController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\SmsNotification';
    }

    /**
     * @ApiDoc(
     *  description="Return all SMS Notification belongs to Application",
     *  views = { "internal" }
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $smsNotifications = $application->getSmsNotifications();

        if($smsNotifications->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No phone number in application'));
        }

        return $this->success($smsNotifications, 'sms_notifications', Response::HTTP_OK, array('Default', 'SmsNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Return single phone number",
     *  views = { "internal" }
     * )
     * @param SmsNotification $smsNotification
     * @return mixed
     * @ParamConverter("smsNotification", class="PizzaBundle\Entity\SmsNotification", options={"id" = "sms_id"})
     */
    public function showAction(SmsNotification $smsNotification = null)
    {
        if ($smsNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Phone number not found'));
        }
        $this->denyAccessUnlessGranted('access', $smsNotification);

        return $this->success($smsNotification, 'sms_notifications', Response::HTTP_OK, array('Default', 'SmsNotification'));
    }


    /**
     * @ApiDoc(
     *  description="Create new phone number",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name"},
     *      {"name"="number", "dataType"="string", "required"=true, "description"="Phone number"},
     *      {"name"="active", "dataType"="boolean", "required"=true, "description"="If notification is active"},
     *  })
     * )
     * @param Request $request
     * @return mixed
     */
    public function createAction(Request $request)
    {
        /** @var Application $application */
        $application = $this->getApplication();
        if($application == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'No application found'));
        }
        
        $form = $this->get('form.factory')->create(new SmsNotificationType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var SmsNotification $smsNotification */
        $smsNotification = $form->getData();

        $smsNotification->setApplication($application);

        $em = $this->getDoctrine()->getManager();
        $em->persist($smsNotification);
        $em->flush();

        return $this->success($smsNotification, 'sms_notifications', Response::HTTP_OK, array('Default', 'SmsNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Update phone number",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name"},
     *      {"name"="number", "dataType"="string", "required"=true, "description"="Phone number"},
     *      {"name"="active", "dataType"="boolean", "required"=true, "description"="If notification is active"},
     *  })
     * )
     * @param Request $request
     * @param SmsNotification $smsNotification
     * @ParamConverter("smsNotification", class="PizzaBundle\Entity\SmsNotification", options={"id" = "sms_id"})
     * @return mixed
     */
    public function updateAction(Request $request, SmsNotification $smsNotification = null)
    {
        if ($smsNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Phone number not found'));
        }
        $this->denyAccessUnlessGranted('access', $smsNotification);

        $form = $this->get('form.factory')->create(new SmsNotificationType(), $smsNotification);
        $formData = json_decode($request->getContent(), true);

        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($smsNotification);
        $em->flush();

        return $this->success($smsNotification, 'sms_notifications', Response::HTTP_OK, array('Default', 'SmsNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Delete phone number",
     *  views = { "internal" }
     *)
     * @param SmsNotification $smsNotification
     * @return mixed|Response
     * @ParamConverter("smsNotification", class="PizzaBundle\Entity\SmsNotification", options={"id" = "sms_id"})
     */
    public function deleteAction(SmsNotification $smsNotification = null)
    {
        if ($smsNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Phone number not found'));
        }
        $this->denyAccessUnlessGranted('access', $smsNotification);

        $em = $this->getDoctrine()->getManager();
        $em->remove($smsNotification);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Phone number properly removed'));
    }
}
