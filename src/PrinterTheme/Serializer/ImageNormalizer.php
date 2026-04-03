<?php

declare(strict_types=1);

namespace PrinterTheme\Serializer;

use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ImageNormalizer implements NormalizerInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $baseUrl = $request ? $request->getSchemeAndHttpHost() : '';
        $path = $object instanceof ImageInterface ? $object->getPath() : null;

        return [
            '@type' => 'Image',
            'id' => $object instanceof ImageInterface ? $object->getId() : null,
            'type' => $object instanceof ImageInterface ? $object->getType() : null,
            'path' => $path ? ($baseUrl . '/media/image/' . $path) : null,
        ];
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
}
