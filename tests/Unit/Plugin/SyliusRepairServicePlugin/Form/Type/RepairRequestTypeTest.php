<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusRepairServicePlugin\Form\Type;

use PHPUnit\Framework\Attributes\Test;
use SyliusRepairServicePlugin\Form\Type\RepairRequestType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class RepairRequestTypeTest extends TypeTestCase
{
    #[Test]
    public function it_builds_customer_and_device_fields(): void
    {
        $form = $this->factory->create(RepairRequestType::class);

        self::assertTrue($form->has('deviceName'));
        self::assertTrue($form->has('deviceBrand'));
        self::assertTrue($form->has('deviceModel'));
        self::assertTrue($form->has('issueDescription'));
        self::assertTrue($form->has('customerName'));
        self::assertTrue($form->has('customerEmail'));
        self::assertTrue($form->has('customerPhoneNumber'));
    }

    protected function getExtensions(): array
    {
        return [new PreloadedExtension([new RepairRequestType()], [])];
    }
}
