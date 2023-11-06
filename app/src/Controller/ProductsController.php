<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Filter\PromotionsFilterInterface;
use App\Serializer\SnakeCaseSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductsController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: ['POST'])]
    public function lowestPrice(Request $request, int $id,
//        SerializerInterface $serializer,
        SnakeCaseSerializer $serializer,
        PromotionsFilterInterface $promotionsFilter,
    ) : Response
    {
        if ($request->headers->has('force_fail')) {
            return new JsonResponse([
                'error' => 'Promotions Engine Failure Message'
            ], $request->headers->get('force_fail'));
        }

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');
        // 2. Pass the Enquiry into a promotions filter
            // the appropriate promotion will be applied
        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry);


        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');
        return new Response($responseContent, 200);

        return new JsonResponse([
            'quantity' => 6,
            'request_location' => 'UK',
            'voucher_code' => '0U812',
            'request_date' => '2022-04-04',
            'product_id' => $id,
            'price' => 100,
            'discounted_price' => 50,
            'promotion_id' => 3,
            'promotion_name' => 'Black Friday half price sale',
        ], 200);
    }
}
