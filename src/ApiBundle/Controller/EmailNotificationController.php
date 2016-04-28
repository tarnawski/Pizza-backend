<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\EmailNotificationType;
use ApiBundle\Form\Type\PromoCodeType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\EmailNotification;
use PizzaBundle\Entity\PromoCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;

/**
 * Class EmailNotificationController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class EmailNotificationController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\EmailNotification';
    }

    /**
     * @ApiDoc(
     *  description="Return all Email Notification belongs to Application",
     *  views = { "internal" }
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $emailNotifications = $application->getEmailNotifications();

        if($emailNotifications->isEmpty()){
            return JsonResponse::create(array('status' => 'Info', 'message' => 'No Email Notification in application'));
        }

        return $this->success($emailNotifications, 'email_notifications', Response::HTTP_OK, array('Default', 'EmailNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Email Notification",
     *  views = { "internal" }
     * )
     * @param EmailNotification $emailNotification
     * @return mixed
     * @ParamConverter("emailNotification", class="PizzaBundle\Entity\EmailNotification", options={"id" = "email_id"})
     */
    public function showAction(EmailNotification $emailNotification = null)
    {
        if ($emailNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Email Notification not found'));
        }
        $this->denyAccessUnlessGranted('access', $emailNotification);

        return $this->success($emailNotification, 'email_notifications', Response::HTTP_OK, array('Default', 'EmailNotification'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Email Notification",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
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
        
        $form = $this->get('form.factory')->create(new EmailNotificationType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var EmailNotification $emailNotification */
        $emailNotification = $form->getData();

        $emailNotification->setApplication($application);

        $em = $this->getDoctrine()->getManager();
        $em->persist($emailNotification);
        $em->flush();

        return $this->success($emailNotification, 'email_notifications', Response::HTTP_OK, array('Default', 'EmailNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Update Email Notification",
     *  views = { "internal" },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *      {"name"="active", "dataType"="boolean", "required"=true, "description"="If notification is active"},
     *  })
     * )
     * @param Request $request
     * @param EmailNotification $emailNotification
     * @ParamConverter("emailNotification", class="PizzaBundle\Entity\EmailNotification", options={"id" = "email_id"})
     * @return mixed
     */
    public function updateAction(Request $request, EmailNotification $emailNotification = null)
    {
        if ($emailNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Email Notification not found'));
        }
        $this->denyAccessUnlessGranted('access', $emailNotification);

        $form = $this->get('form.factory')->create(new EmailNotificationType(), $emailNotification);
        $formData = json_decode($request->getContent(), true);

        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($emailNotification);
        $em->flush();

        return $this->success($emailNotification, 'email_notifications', Response::HTTP_OK, array('Default', 'EmailNotification'));
    }

    /**
     * @ApiDoc(
     *  description="Delete PromoCode",
     *  views = { "internal" }
     *)
     * @param EmailNotification $emailNotification
     * @return mixed|Response
     * @ParamConverter("emailNotification", class="PizzaBundle\Entity\EmailNotification", options={"id" = "email_id"})
     */
    public function deleteAction(EmailNotification $emailNotification = null)
    {
        if ($emailNotification == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Email Notification not found'));
        }
        $this->denyAccessUnlessGranted('access', $emailNotification);

        $em = $this->getDoctrine()->getManager();
        $em->remove($emailNotification);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Email Notification properly removed'));
    }
}
