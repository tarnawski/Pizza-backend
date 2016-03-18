<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseApiController
 * @package ApiBundle\Controller
 */
abstract class BaseApiController extends FOSRestController
{
    /**
     * Abstract method to get name of entity class
     *
     * @return string
     */
    abstract public function getEntityClassName();

    protected function getErrorMessages(Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $errors[$key] = $error->getMessage();
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $key = sprintf('%s[%s]', $form->getName(), $child->getName());
                $errors[$key] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @param array $data
     * @param int $code
     * @return mixed
     */
    protected function error($data = array(), $code = Response::HTTP_BAD_REQUEST)
    {
        $view = $this->view()
            ->setStatusCode($code)
            ->setData($data);

        return $this->handleView($view);
    }

    /**
     * @param $data
     * @param $templateVar
     * @param int $code
     * @param array $serializationGroups
     * @return Response
     */
    protected function success($data, $templateVar, $code = Response::HTTP_OK, array $serializationGroups = array('Default'))
    {
        $view = $this->view()
            ->setTemplateVar($templateVar)
            ->setStatusCode($code)
            ->setData($data)
            ->setSerializationContext(SerializationContext::create()->setGroups($serializationGroups));

        return $this->handleView($view);
    }
    
    /**
     * @param $entityClass
     * @return mixed
     */
    public function getRepository($entityClass)
    {
        return $this->getDoctrine()->getManager()->getRepository($entityClass);
    }

    /**
     * @return  Application
     */
    public function getApplication()
    {
        /** @var User $current_user */
        $current_user = $this->getUser();
        /** @var Application $application */
        $application = $current_user->getApplication();

        return $application;
    }
}
