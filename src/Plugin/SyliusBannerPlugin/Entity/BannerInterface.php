<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface BannerInterface extends ResourceInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getSubtitle(): ?string;

    public function setSubtitle(?string $subtitle): void;

    public function getLink(): ?string;

    public function setLink(?string $link): void;

    public function getImagePath(): ?string;

    public function setImagePath(?string $imagePath): void;

    public function getPosition(): ?string;

    public function setPosition(?string $position): void;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function getSortOrder(): int;

    public function setSortOrder(int $sortOrder): void;
}
