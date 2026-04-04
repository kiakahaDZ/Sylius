<?php

declare(strict_types=1);

namespace PrinterTheme\Serializer;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ImageNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CacheManager $cacheManager,
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $baseUrl = $request ? $request->getSchemeAndHttpHost() : '';
        $path = $object instanceof ImageInterface ? $object->getPath() : null;

        $data = [
            '@type' => 'Image',
            'id' => $object instanceof ImageInterface ? $object->getId() : null,
            'type' => $object instanceof ImageInterface ? $object->getType() : null,
            'path' => $path ? ($baseUrl . '/media/image/' . $path) : null,
        ];

        // Add filtered image URLs for Flutter API responsive delivery
        if ($path !== null) {
            $data['filters'] = [
                'original' => $this->getFilterUrl($path, 'sylius_shop_product_original'),
                'large' => $this->getFilterUrl($path, 'sylius_shop_product_large_thumbnail'),
                'card' => $this->getFilterUrl($path, 'sylius_shop_product_card'),
                'small' => $this->getFilterUrl($path, 'sylius_shop_product_small_thumbnail'),
            ];
        }

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ImageInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ImageInterface::class => true,
        ];
    }

    private function getFilterUrl(string $path, string $filter): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $baseUrl = $request ? $request->getSchemeAndHttpHost() : '';

        return $baseUrl . $this->cacheManager->getBrowserPath(
            '/media/image/' . $path,
            $filter,
        );
    }
}
