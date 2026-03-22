<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Command;

use SyliusBestSellerPlugin\Service\BestSellerCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'sylius:best-sellers:recalculate', description: 'Recalculate best sellers data')]
class RecalculateBestSellersCommand extends Command
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
            ->setDescription('Recalculate best sellers data')
            ->addArgument('period', InputArgument::OPTIONAL, 'Period to recalculate (daily, weekly, monthly, yearly, all_time)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $period = $input->getArgument('period');

        if ($period) {
            $output->writeln(sprintf('Recalculating best sellers for period: %s', $period));
            $this->bestSellerCalculator->calculateForPeriod($period);
            $output->writeln('Done!');
        }
        else {
            $output->writeln('Recalculating all best sellers...');
            $this->bestSellerCalculator->calculateAll();
            $output->writeln('Done!');
        }

        return Command::SUCCESS;
    }
}