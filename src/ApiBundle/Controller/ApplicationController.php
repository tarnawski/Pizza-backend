<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Application;

/**
 * Class ApplicationController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class ApplicationController extends BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Application';
    }

    /**
     * @ApiDoc(
     *  description="Return user application",
     * )
     */
    public function showAction()
    {
        /** @var User $current_user */
        $current_user = $this->getUser();
        /** @var Application $application */
        $application = $current_user->getApplication();

        return $this->success($application, 'application', Response::HTTP_OK, array('Default', 'Application'));

    }

}
