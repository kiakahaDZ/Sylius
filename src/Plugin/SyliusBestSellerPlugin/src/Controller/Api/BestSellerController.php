<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Controller\Api;

use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use SyliusBestSellerPlugin\Service\BestSellerCacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\RequestStack;

class BestSellerController extends AbstractController
{
    private RequestStack $requestStack;

    private BestSellerCacheManager $bestSellerCacheManager;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        BestSellerCacheManager $bestSellerCacheManager,
        ProductRepositoryInterface $productRepository,
        RequestStack $requestStack
    ) {
        $this->bestSellerCacheManager = $bestSellerCacheManager;
        $this->productRepository = $productRepository;
        $this->requestStack = $requestStack;
    }


    public function getBestSellersAction(Request $request): Response

    {
        $period = (string) $request->query->get('period', 'weekly');
        $limit = (int) $request->query->get('limit', 10);
        $channelId = $request->query->get('channelId') ? (int) $request->query->get('channelId') : null;

        $bestSellers = $this->bestSellerCacheManager->getBestSellers($period, $limit, $channelId);

        $response = [];
        foreach ($bestSellers as $item) {
            $product = $item['product'];
            $response[] = [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'price' => $product->getVariants()->first()?->getChannelPricings()->first()?->getPrice() / 100, // Minimal extraction
                'currency' => $product->getVariants()->first()?->getChannelPricings()->first()?->getChannel()?->getBaseCurrency()?->getCode() ?? 'USD',
                'image' => $this->getProductImageUrl($product),
                'total_sales' => $item['total_sales'],
                'total_quantity' => $item['total_quantity'],
                'total_revenue' => $item['total_revenue'],
                'position' => $item['position'],
            ];
        }

        return $this->json([
            'status' => 'success',
            'data' => $response,
            'meta' => [
                'period' => $period,
                'limit' => $limit,
                'total' => count($response),
            ]
        ]);
    }

    public function getTopProductAction(Request $request): Response

    {
        $period = (string) $request->query->get('period', 'weekly');

        $topProduct = $this->bestSellerCacheManager->getBestSellers($period, 1);

        if (empty($topProduct)) {
            return $this->json([
                'status' => 'success',
                'data' => null,
                'message' => 'No products found'
            ]);
        }

        $item = $topProduct[0];
        $product = $item['product'];

        return $this->json([
            'status' => 'success',
            'data' => [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'image' => $this->getProductImageUrl($product),
                'total_sales' => $item['total_sales'],
                'total_quantity' => $item['total_quantity'],
                'total_revenue' => $item['total_revenue'],
            ]
        ]);
    }

    public function getProductStatsAction(int $productId, Request $request): Response

    {
        $period = (string) $request->query->get('period', 'weekly');

        $product = $this->productRepository->find($productId);

        if (!$product) {
            return $this->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $provider = $this->bestSellerCacheManager->getProvider();
        $stats = $provider->getProductStats($product, $period);

        return $this->json([
            'status' => 'success',
            'data' => [
                'product_id' => $productId,
                'period' => $period,
                'stats' => $stats,
            ]
        ]);
    }

    private function getProductImageUrl($product): ?string
    {
        $image = $product->getImages()->first();
        if (!$image) {
            return null;
        }

        $path = $image->getPath();
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request) {
            return $request->getSchemeAndHttpHost() . '/media/image/' . $path;
        }

        return '/media/image/' . $path;
    }

}