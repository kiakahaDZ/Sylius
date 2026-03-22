<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_offer")
 */
class Offer implements ResourceInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private ?string $link = null;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private ?string $badge = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $type = null; // 'card', 'banner', 'popup', 'sidebar'

    /**
     * @ORM\Column(type="integer")
     */
    private int $position = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $startDate = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $endDate = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetUrls = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetProducts = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetCategories = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetChannels = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetLocales = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $targetUserGroups = [];

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $backgroundColor = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $textColor = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $buttonText = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $buttonColor = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $views = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $clicks = 0;

    /**
     * @ORM\Column(type="float")
     */
    private float $conversionRate = 0.0;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->enabled = true;
        $this->position = 0;
        $this->type = 'card';
        $this->targetUrls = [];
        $this->targetProducts = [];
        $this->targetCategories = [];
        $this->targetChannels = [];
        $this->targetLocales = [];
        $this->targetUserGroups = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(?string $badge): void
    {
        $this->badge = $badge;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getTargetUrls(): array
    {
        return $this->targetUrls;
    }

    public function setTargetUrls(array $targetUrls): void
    {
        $this->targetUrls = $targetUrls;
    }

    public function getTargetProducts(): array
    {
        return $this->targetProducts;
    }

    public function setTargetProducts(array $targetProducts): void
    {
        $this->targetProducts = $targetProducts;
    }

    public function getTargetCategories(): array
    {
        return $this->targetCategories;
    }

    public function setTargetCategories(array $targetCategories): void
    {
        $this->targetCategories = $targetCategories;
    }

    public function getTargetChannels(): array
    {
        return $this->targetChannels;
    }

    public function setTargetChannels(array $targetChannels): void
    {
        $this->targetChannels = $targetChannels;
    }

    public function getTargetLocales(): array
    {
        return $this->targetLocales;
    }

    public function setTargetLocales(array $targetLocales): void
    {
        $this->targetLocales = $targetLocales;
    }

    public function getTargetUserGroups(): array
    {
        return $this->targetUserGroups;
    }

    public function setTargetUserGroups(array $targetUserGroups): void
    {
        $this->targetUserGroups = $targetUserGroups;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): void
    {
        $this->textColor = $textColor;
    }

    public function getButtonText(): ?string
    {
        return $this->buttonText;
    }

    public function setButtonText(?string $buttonText): void
    {
        $this->buttonText = $buttonText;
    }

    public function getButtonColor(): ?string
    {
        return $this->buttonColor;
    }

    public function setButtonColor(?string $buttonColor): void
    {
        $this->buttonColor = $buttonColor;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    public function incrementViews(): void
    {
        $this->views++;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function setClicks(int $clicks): void
    {
        $this->clicks = $clicks;
    }

    public function incrementClicks(): void
    {
        $this->clicks++;
        $this->updateConversionRate();
    }

    public function getConversionRate(): float
    {
        return $this->conversionRate;
    }

    public function setConversionRate(float $conversionRate): void
    {
        $this->conversionRate = $conversionRate;
    }

    public function updateConversionRate(): void
    {
        if ($this->views > 0) {
            $this->conversionRate = ($this->clicks / $this->views) * 100;
        }
        else {
            $this->conversionRate = 0;
        }
    }

    public function isActive(): bool
    {
        $now = new \DateTime();

        if (!$this->isEnabled()) {
            return false;
        }

        if ($this->startDate && $this->startDate > $now) {
            return false;
        }

        if ($this->endDate && $this->endDate < $now) {
            return false;
        }

        return true;
    }

    public function isTargeted(string $url, ?string $locale = null, ?int $userId = null): bool
    {
        // Check URL targeting
        if (!empty($this->targetUrls)) {
            $matched = false;
            foreach ($this->targetUrls as $targetUrl) {
                if (fnmatch($targetUrl, $url)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                return false;
            }
        }

        // Check locale targeting
        if (!empty($this->targetLocales) && $locale) {
            if (!in_array($locale, $this->targetLocales)) {
                return false;
            }
        }

        // Check user group targeting (implement based on your user system)
        // if (!empty($this->targetUserGroups) && $userId) {
        //     // Check user group logic
        // }

        return true;
    }
}