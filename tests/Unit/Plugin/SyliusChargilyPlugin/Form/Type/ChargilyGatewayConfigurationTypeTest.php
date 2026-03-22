<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusChargilyPlugin\Form\Type;

use PHPUnit\Framework\Attributes\Test;
use SyliusChargilyPlugin\Form\Type\ChargilyGatewayConfigurationType;
use SyliusChargilyPlugin\Provider\ChargilyGatewayFactory;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class ChargilyGatewayConfigurationTypeTest extends TypeTestCase
{
    #[Test]
    public function it_builds_all_gateway_configuration_fields(): void
    {
        $form = $this->factory->create(ChargilyGatewayConfigurationType::class);

        self::assertTrue($form->has('mode'));
        self::assertTrue($form->has('public_key'));
        self::assertTrue($form->has('secret_key'));
        self::assertTrue($form->has('payment_method'));
        self::assertTrue($form->has('locale'));
        self::assertTrue($form->has('fees_allocation'));
    }

    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([
                new ChargilyGatewayConfigurationType(new ChargilyGatewayFactory()),
            ], []),
        ];
    }
}
