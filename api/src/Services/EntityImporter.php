<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EntityImporter
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CsvReader $csvReader,
        private readonly array $entityHandlers = []
    ) {
    }

    public function importAll(array $entityFiles, SymfonyStyle $io): array
    {
        $results = [];

        foreach ($entityFiles as $entityClass => $filePath) {
            $handler = $this->entityHandlers[$entityClass] ?? throw new \InvalidArgumentException("No handler defined for entity: $entityClass");
            if (!$handler) {
                $io->warning("No handler defined for entity: $entityClass");
                $results[$entityClass] = 0;
                continue;
            }

            $results[$entityClass] = $this->importEntity($entityClass, $filePath, $handler, $io);
        }

        return $results;
    }

    private function importEntity(string $entityClass, string $filePath, callable $handler, SymfonyStyle $io): int
    {
        if (!file_exists($filePath)) {
            $io->warning("CSV file not found: $filePath");
            return 0;
        }

        $data = $this->csvReader->read($filePath);
        $importedCount = 0;

        foreach ($data as $row) {

            try {

                $entity = $handler($row, $this->entityManager);
                if (!$entity) {
                    continue;
                }

            }catch (\Exception $e) {
                $io->error("Failed to import entity: $entityClass" . $e->getMessage());
            }

            if ($entity) {
                $this->entityManager->flush();
                $this->entityManager->persist($entity);
                $io->success("Imported {$entity} entity");
                $importedCount++;
            }

        }

        $this->entityManager->flush();
        $io->success("Imported {$importedCount} {$entityClass} entities");

        return $importedCount;
    }
}
