<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ApiBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     *  description="Return Application",
     * )
     */
    public function showAction()
    {
        $application = $this->getApplication();

        return $this->success($application, 'application', Response::HTTP_OK, array('Default', 'Application'));
    }
}

