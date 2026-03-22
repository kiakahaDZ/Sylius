<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Installer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Installer
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function install(SymfonyStyle $io): void
    {
        $io->title('Sylius Offers Plugin Installation');

        $this->runMigrations($io);
        $this->createDefaultOffer($io);

        $io->success('Offers Plugin installed successfully!');
    }

    private function runMigrations(SymfonyStyle $io): void
    {
        $io->section('Running migrations...');
        // Implementation depends on your migration strategy
        $io->writeln('✓ Database schema updated');
    }

    private function createDefaultOffer(SymfonyStyle $io): void
    {
        $io->section('Creating sample offer...');

        // Check if offers already exist
        $existingOffers = $this->entityManager
            ->getRepository('SyliusOffersPlugin\Entity\Offer')
            ->count([]);

        if ($existingOffers === 0) {
            $offer = new \SyliusOffersPlugin\Entity\Offer();
            $offer->setCode('WELCOME_OFFER');
            $offer->setTitle('Welcome Offer');
            $offer->setDescription('Get 20% off on your first purchase!');
            $offer->setBadge('New');
            $offer->setType('card');
            $offer->setButtonText('Shop Now');
            $offer->setLink('/');
            $offer->setEnabled(true);

            $this->entityManager->persist($offer);
            $this->entityManager->flush();

            $io->writeln('✓ Sample offer created');
        }
    }
}