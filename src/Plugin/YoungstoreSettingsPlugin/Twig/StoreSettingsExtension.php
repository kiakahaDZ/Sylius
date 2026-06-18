<?php
namespace YoungstoreSettingsPlugin\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use YoungstoreSettingsPlugin\Entity\StoreSettings;

class StoreSettingsExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_store_settings', [$this, 'getStoreSettings']),
        ];
    }

    public function getStoreSettings(): ?StoreSettings
    {
        // Try to fetch the first settings record
        return $this->entityManager->getRepository(StoreSettings::class)->findOneBy([]);
    }
}
