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
        return 'OAuthBundle\Entity\Application';
    }

    /**
     * @ApiDoc(
     *  description="Return user application",
     * )
     */
    public function showAction()
    {
        $current_user = $this->getUser();
        $application = $current_user->getApplication();
        var_dump($application);
    }

}
