<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager )
    {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: ['POST'])]
    public function lowestPrice(Request                   $request, int $id,
//        SerializerInterface $serializer,
                                DTOSerializer             $serializer,
                                PromotionsFilterInterface $promotionsFilter,
                                PromotionCache            $promotionCache
    ) : Response
    {
        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        /** @var ProductRepository $productRepository */
        $productRepository = $this->entityManager->getRepository(Product::class);

        $product = $productRepository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, 200, ['Content-Type' => 'application/json']);
    }
}
