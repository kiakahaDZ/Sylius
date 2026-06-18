<?php
namespace YoungstoreSettingsPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: "youngstore_settings")]
class StoreSettings implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $frontstoreLogo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $adminLogo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $apkLink = null;

    // --- Footer Properties ---
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $footerLogo = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $footerAboutText = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contactAddress = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contactEmail = null;

    // --- Social Networks ---
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $socialFacebookEnabled = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $socialFacebookUrl = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $socialInstagramEnabled = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $socialInstagramUrl = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $socialTiktokEnabled = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $socialTiktokUrl = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $socialWhatsappEnabled = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $socialWhatsappUrl = null;

    // --- New Collection ---
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $newCollectionTitle = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $newCollectionImage1 = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $newCollectionImage2 = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $newCollectionImage3 = null;

    public function getId(): ?int { return $this->id; }

    public function getFrontstoreLogo(): ?string { return $this->frontstoreLogo; }
    public function setFrontstoreLogo(?string $frontstoreLogo): self { $this->frontstoreLogo = $frontstoreLogo; return $this; }

    public function getAdminLogo(): ?string { return $this->adminLogo; }
    public function setAdminLogo(?string $adminLogo): self { $this->adminLogo = $adminLogo; return $this; }

    public function getApkLink(): ?string { return $this->apkLink; }
    public function setApkLink(?string $apkLink): self { $this->apkLink = $apkLink; return $this; }

    public function getFooterLogo(): ?string { return $this->footerLogo; }
    public function setFooterLogo(?string $footerLogo): self { $this->footerLogo = $footerLogo; return $this; }

    public function getFooterAboutText(): ?string { return $this->footerAboutText; }
    public function setFooterAboutText(?string $footerAboutText): self { $this->footerAboutText = $footerAboutText; return $this; }

    public function getContactAddress(): ?string { return $this->contactAddress; }
    public function setContactAddress(?string $contactAddress): self { $this->contactAddress = $contactAddress; return $this; }

    public function getContactPhone(): ?string { return $this->contactPhone; }
    public function setContactPhone(?string $contactPhone): self { $this->contactPhone = $contactPhone; return $this; }

    public function getContactEmail(): ?string { return $this->contactEmail; }
    public function setContactEmail(?string $contactEmail): self { $this->contactEmail = $contactEmail; return $this; }

    public function getSocialFacebookEnabled(): ?bool { return $this->socialFacebookEnabled; }
    public function setSocialFacebookEnabled(?bool $socialFacebookEnabled): self { $this->socialFacebookEnabled = $socialFacebookEnabled; return $this; }

    public function getSocialFacebookUrl(): ?string { return $this->socialFacebookUrl; }
    public function setSocialFacebookUrl(?string $socialFacebookUrl): self { $this->socialFacebookUrl = $socialFacebookUrl; return $this; }

    public function getSocialInstagramEnabled(): ?bool { return $this->socialInstagramEnabled; }
    public function setSocialInstagramEnabled(?bool $socialInstagramEnabled): self { $this->socialInstagramEnabled = $socialInstagramEnabled; return $this; }

    public function getSocialInstagramUrl(): ?string { return $this->socialInstagramUrl; }
    public function setSocialInstagramUrl(?string $socialInstagramUrl): self { $this->socialInstagramUrl = $socialInstagramUrl; return $this; }

    public function getSocialTiktokEnabled(): ?bool { return $this->socialTiktokEnabled; }
    public function setSocialTiktokEnabled(?bool $socialTiktokEnabled): self { $this->socialTiktokEnabled = $socialTiktokEnabled; return $this; }

    public function getSocialTiktokUrl(): ?string { return $this->socialTiktokUrl; }
    public function setSocialTiktokUrl(?string $socialTiktokUrl): self { $this->socialTiktokUrl = $socialTiktokUrl; return $this; }

    public function getSocialWhatsappEnabled(): ?bool { return $this->socialWhatsappEnabled; }
    public function setSocialWhatsappEnabled(?bool $socialWhatsappEnabled): self { $this->socialWhatsappEnabled = $socialWhatsappEnabled; return $this; }

    public function getSocialWhatsappUrl(): ?string { return $this->socialWhatsappUrl; }
    public function setSocialWhatsappUrl(?string $socialWhatsappUrl): self { $this->socialWhatsappUrl = $socialWhatsappUrl; return $this; }

    public function getNewCollectionTitle(): ?string { return $this->newCollectionTitle; }
    public function setNewCollectionTitle(?string $newCollectionTitle): self { $this->newCollectionTitle = $newCollectionTitle; return $this; }

    public function getNewCollectionImage1(): ?string { return $this->newCollectionImage1; }
    public function setNewCollectionImage1(?string $newCollectionImage1): self { $this->newCollectionImage1 = $newCollectionImage1; return $this; }

    public function getNewCollectionImage2(): ?string { return $this->newCollectionImage2; }
    public function setNewCollectionImage2(?string $newCollectionImage2): self { $this->newCollectionImage2 = $newCollectionImage2; return $this; }

    public function getNewCollectionImage3(): ?string { return $this->newCollectionImage3; }
    public function setNewCollectionImage3(?string $newCollectionImage3): self { $this->newCollectionImage3 = $newCollectionImage3; return $this; }
}
