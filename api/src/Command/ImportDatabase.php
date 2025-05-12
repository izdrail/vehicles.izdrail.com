<?php

namespace App\Command;

use App\Entity\Brand;
use App\Entity\Automobile;
use App\Entity\Engine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\EntityImporter;
use App\Service\TableTruncator;

#[AsCommand(
    name: 'import:database',
    description: 'Imports data from local CSV fixtures',
)]
class ImportDatabase extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityImporter $entityImporter,
        private readonly TableTruncator $tableTruncator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'truncate',
            't',
            InputOption::VALUE_NONE,
            'Truncate existing tables before importing'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            if ($input->getOption('truncate')) {
                $this->tableTruncator->truncateTables(['engines', 'automobiles', 'brands']);
                $io->note('Existing tables have been truncated.');
            }

            $results = $this->entityImporter->importAll([
                Brand::class => __DIR__ . '/../../migrations/data/brands.csv',
                Automobile::class => __DIR__ . '/../../migrations/data/automobiles.csv',
                Engine::class => __DIR__ . '/../../migrations/data/engines.csv',
            ], $io);

            $io->success(sprintf(
                "Import complete. Brands: %d, Automobiles: %d, Engines: %d",
                $results[Brand::class],
                $results[Automobile::class],
                $results[Engine::class]
            ));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred during database import: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
