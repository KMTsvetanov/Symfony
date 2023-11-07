<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\Entity\Movie;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\PromotionRepository;
use App\Serializer\SnakeCaseSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
        PromotionCache $promotionCache
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

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, 200, ['Content-Type' => 'application/json']);
    }
}
