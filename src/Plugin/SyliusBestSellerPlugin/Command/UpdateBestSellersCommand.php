<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Command;

use SyliusBestSellerPlugin\Service\BestSellerCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sylius:best-sellers:update', description: 'Update best sellers data based on completed orders')]
class UpdateBestSellersCommand extends Command
{

    private BestSellerCalculator $bestSellerCalculator;

    public function __construct(BestSellerCalculator $bestSellerCalculator)
    {
        parent::__construct();
        $this->bestSellerCalculator = $bestSellerCalculator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Update best sellers data based on completed orders')
            ->setHelp('This command updates the best sellers cache with latest order data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting best sellers update...');

        try {
            // Update all periods
            $periods = ['daily', 'weekly', 'monthly', 'yearly', 'all_time'];

            foreach ($periods as $period) {
                $output->writeln(sprintf('Updating %s best sellers...', $period));
                $this->bestSellerCalculator->calculateForPeriod($period);
                $output->writeln(sprintf('✓ %s best sellers updated', $period));
            }

            $output->writeln('Best sellers update completed successfully!');

            return Command::SUCCESS;
        }
        catch (\Exception $e) {
            $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}