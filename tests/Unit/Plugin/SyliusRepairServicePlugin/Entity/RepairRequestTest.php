<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusRepairServicePlugin\Entity;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SyliusRepairServicePlugin\Entity\RepairRequest;

final class RepairRequestTest extends TestCase
{
    #[Test]
    public function it_starts_with_submitted_status_and_generated_code(): void
    {
        $repairRequest = new RepairRequest();

        self::assertSame(RepairRequest::STATUS_SUBMITTED, $repairRequest->getStatus());
        self::assertNotSame('', $repairRequest->getCode());
    }
}
