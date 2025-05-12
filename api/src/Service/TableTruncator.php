<?php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Psr\Log\LoggerInterface;

class TableTruncator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {}

    public function truncateTables(array $tables): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        try {
            $connection->beginTransaction();

            // For MySQL – disable FK checks before truncating
            if ($platform instanceof MySQLPlatform) {
                $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
                $this->logger->info('🔒 Disabled MySQL foreign key checks');
            }

            foreach ($tables as $table) {
                // TRUNCATE with cascade if supported
                $truncateSql = $platform->getTruncateTableSQL($table, true);
                $connection->executeStatement($truncateSql);
                $this->logger->info("🧹 Truncated: {$table}");

                // PostgreSQL: reset sequence (assumes default naming)
                if ($platform instanceof PostgreSQLPlatform) {
                    $sequenceName = $table . '_id_seq';
                    $resetSql = "ALTER SEQUENCE {$sequenceName} RESTART WITH 1";
                    $connection->executeStatement($resetSql);
                    $this->logger->info("🔁 Reset sequence: {$sequenceName}");
                }
            }

            // Re-enable foreign key checks in MySQL
            if ($platform instanceof MySQLPlatform) {
                $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
                $this->logger->info('🔓 Re-enabled MySQL foreign key checks');
            }

            $connection->commit();
            $this->logger->info('✅ Tables truncated and ID sequences reset.');
        } catch (Exception $e) {
            $connection->rollBack();
            $this->logger->error('❌ Table truncation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
