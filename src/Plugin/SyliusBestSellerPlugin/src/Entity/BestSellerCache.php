<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: "sylius_best_seller_cache")]
#[ORM\Index(name: "idx_period_total", columns: ["period", "total_sales"])]
#[ORM\Index(name: "idx_product_period", columns: ["product_id", "period"])]
class BestSellerCache implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "product_id", type: "integer")]
    private ?int $productId = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $period = null; // 'daily', 'weekly', 'monthly', 'yearly', 'all_time'

    #[ORM\Column(name: "total_sales", type: "integer")]
    private int $totalSales = 0;

    #[ORM\Column(name: "total_quantity", type: "integer")]
    private int $totalQuantity = 0;

    #[ORM\Column(name: "total_revenue", type: "decimal", precision: 10, scale: 2)]
    private float $totalRevenue = 0.0;

    #[ORM\Column(type: "integer")]
    private int $position = 0;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getTotalSales(): int
    {
        return $this->totalSales;
    }

    public function setTotalSales(int $totalSales): void
    {
        $this->totalSales = $totalSales;
    }

    public function incrementTotalSales(int $amount = 1): void
    {
        $this->totalSales += $amount;
    }

    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    public function setTotalQuantity(int $totalQuantity): void
    {
        $this->totalQuantity = $totalQuantity;
    }

    public function incrementTotalQuantity(int $amount): void
    {
        $this->totalQuantity += $amount;
    }

    public function getTotalRevenue(): float
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(float $totalRevenue): void
    {
        $this->totalRevenue = $totalRevenue;
    }

    public function addRevenue(float $revenue): void
    {
        $this->totalRevenue += $revenue;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}