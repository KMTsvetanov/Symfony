<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Movie;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Serializer\SnakeCaseSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager )
    {
    }

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

        $productRepository = $this->entityManager->getRepository(Product::class);

        $product = $productRepository->find($id); // add Error handling for not found product

        $lowestPriceEnquiry->setProduct($product);

        $promotionRepository = $this->entityManager->getRepository(Promotion::class);

        $promotions = $promotionRepository->findValidForProduct(
            $product,
            date_create_immutable($lowestPriceEnquiry->getRequestDate())
        );

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');
        return new Response($responseContent, 200, ['Content-Type' => 'application/json']);

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
