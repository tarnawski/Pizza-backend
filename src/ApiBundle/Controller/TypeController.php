<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\TypeType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PizzaBundle\Entity\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;

/**
 * Class TypeController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class TypeController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Type';
    }

    /**
     * @ApiDoc(
     *  description="Return all Types belongs to Application",
     * )
     * @return mixed
     */
    public function indexAction()
    {
        $application = $this->getApplication();
        $types = $application->getTypes();

        return $this->success($types, 'type', Response::HTTP_OK, array('Default', 'Type'));
    }

    /**
     * @ApiDoc(
     *  description="Return single Type",
     * )
     * @param Type $type
     * @return mixed
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function showAction(Type $type = null)
    {
        if ($type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Type not found'));
        }
        $this->denyAccessUnlessGranted('access', $type);

        return $this->success($type, 'type', Response::HTTP_OK, array('Default', 'Type'));
    }


    /**
     * @ApiDoc(
     *  description="Create new Type",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Type name"},
     *  })
     * )
     * @param Request $request
     * @return mixed
     */
    public function createAction(Request $request)
    {
        /** @var Application $application */
        $application = $this->getApplication();

        $form = $this->get('form.factory')->create(new TypeType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Type $type */
        $type = $form->getData();

        $type->setApplication($application);

        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();

        return $this->success($type, 'type', Response::HTTP_OK, array('Default', 'Type'));
    }

    /**
     * @ApiDoc(
     *  description="Update Type",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Type name"},
     *  })
     * )
     * @param Request $request
     * @param Type $type
     * @return mixed|Response
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function updateAction(Request $request, Type $type = null)
    {
        if ($type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Type not found'));
        }
        $this->denyAccessUnlessGranted('access', $type);

        $form = $this->get('form.factory')->create(new TypeType(), $type);
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();

        return $this->success($type, 'type', Response::HTTP_OK, array('Default', 'Type'));
    }

    /**
     * @ApiDoc(
     *  description="Delete Type",
     *)
     * @param Type $type
     * @return mixed|Response
     * @ParamConverter("type", class="PizzaBundle\Entity\Type", options={"id" = "type_id"})
     */
    public function deleteAction(Type $type = null)
    {
        if ($type == null){
            return JsonResponse::create(array('status' => 'Error', 'message' => 'Type not found'));
        }
        $this->denyAccessUnlessGranted('access', $type);

        $em = $this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();

        return JsonResponse::create(array('status' => 'Removed', 'message' => 'Type properly removed'));
    }
}
