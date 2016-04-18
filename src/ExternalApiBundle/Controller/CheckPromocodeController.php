<?php

namespace ExternalApiBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use ExternalApiBundle\Form\Type\CompleteOrderType;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PizzaBundle\Entity\Application;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Order;
use PizzaBundle\Entity\Item;
use PizzaBundle\Entity\Type;
use PizzaBundle\Entity\Product;
use PizzaBundle\Entity\PromoCode;

/**
 * Class GetApplicationController
 * @package ExternalApiBundle\Controller
 * @ApiDoc()
 */
class CheckPromoCodeController extends  BaseApiController
{
    public function getEntityClassName()
    {
        return 'PizzaBundle\Entity\Application';
    }

    /**
     * @ApiDoc(
     *  description="Return promo code details if exist",
     *  views = { "external" }
     * )
     * @param Application $application
     * @param Request $request
     * @return mixed
     * @ParamConverter("application", class="PizzaBundle\Entity\Application", options={"id" = "application_id"})
     */
    public function checkAction(Application $application, Request $request)
    {
        $formData = json_decode($request->getContent(), true);

        $promoCodeRepository = $this->getRepository(PromoCode::class);
        /** @var PromoCode $promoCode */
        $promoCode = $promoCodeRepository->getPromoCodeByCode($application, $formData['code']);
        if($promoCode != null && $promoCode->isAvailable()){
            return $this->success($promoCode, 'promocode', Response::HTTP_OK, array('Default', 'PromoCode'));
        }

        return JsonResponse::create(array('status' => 'Not found'));
    }
}
