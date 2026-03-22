<?php

declare(strict_types=1);

namespace SyliusBestSellerPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: "sylius_best_seller_config")]
class BestSellerConfig implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "display_on_homepage", type: "boolean")]
    private bool $displayOnHomepage = true;

    #[ORM\Column(name: "number_of_products", type: "integer")]
    private int $numberOfProducts = 10;

    #[ORM\Column(type: "string", length: 50)]
    private string $period = 'weekly';

    #[ORM\Column(type: "string", length: 50)]
    private string $sortBy = 'total_sales';

    #[ORM\Column(name: "cache_ttl", type: "integer")]
    private int $cacheTtl = 3600;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isDisplayOnHomepage(): bool
    {
        return $this->displayOnHomepage;
    }

    public function setDisplayOnHomepage(bool $displayOnHomepage): void
    {
        $this->displayOnHomepage = $displayOnHomepage;
    }

    public function getNumberOfProducts(): int
    {
        return $this->numberOfProducts;
    }

    public function setNumberOfProducts(int $numberOfProducts): void
    {
        $this->numberOfProducts = $numberOfProducts;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    public function setCacheTtl(int $cacheTtl): void
    {
        $this->cacheTtl = $cacheTtl;
    }
}
