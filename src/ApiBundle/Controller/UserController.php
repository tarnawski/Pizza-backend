<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Form\Type\LoginType;
use ApiBundle\Form\Type\RegisterType;
use PizzaBundle\Model\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UserController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class UserController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'OAuthBundle\Entity\User';
    }

    /**
     * @ApiDoc(
     *  description="Return information about current user ",
     * )
     */
    public function profileAction()
    {
        $current_user = $this->getUser();

        return $this->success($current_user, 'user', Response::HTTP_OK, array('Default', 'Profile'));
    }

    /**
     * @param Request $request
     * @return Response
     * @ApiDoc(
     *  description="This method register new user",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="User name"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="User email"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="User password"
     *      },
     *      {
     *          "name"="client_id",
     *          "dataType"="string",
     *      },
     *         {
     *          "name"="client_secret",
     *          "dataType"="string",
     *      },
     *  }
     * )
     */
    public function registerAction(Request $request)
    {
        $form = $this->get('form.factory')->create(new RegisterType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Register $data */
        $data = $form->getData();

        $userFactory = $this->get('pizza_user_factory');
        $user = $userFactory->buildAfterRegistration($data->username, $data->email, $data->password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $accessToken = $this->get('pizza_token_builder');
        $token = $accessToken->build($user, $data);

        return $token;
    }
}
