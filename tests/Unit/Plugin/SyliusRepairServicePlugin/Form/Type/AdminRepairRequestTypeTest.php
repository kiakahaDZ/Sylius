<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusRepairServicePlugin\Form\Type;

use PHPUnit\Framework\Attributes\Test;
use SyliusRepairServicePlugin\Form\Type\AdminRepairRequestType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class AdminRepairRequestTypeTest extends TypeTestCase
{
    #[Test]
    public function it_builds_admin_status_and_notes_fields(): void
    {
        $form = $this->factory->create(AdminRepairRequestType::class);

        self::assertTrue($form->has('status'));
        self::assertTrue($form->has('diagnosisNotes'));
        self::assertTrue($form->has('repairNotes'));
    }

    protected function getExtensions(): array
    {
        return [new PreloadedExtension([new AdminRepairRequestType()], [])];
    }
}
