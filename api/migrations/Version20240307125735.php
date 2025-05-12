<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307125735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create automobiles, brands, and engines tables';
    }

    public function up(Schema $schema): void
    {
        // Create brands table
        $this->addSql('CREATE TABLE brands (
            id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
            url_hash VARCHAR(191) NOT NULL,
            url TEXT NOT NULL,
            name VARCHAR(191) NOT NULL,
            logo TEXT DEFAULT NULL,
            deleted_at TIMESTAMP DEFAULT NULL,
            created_at TIMESTAMP DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT NULL,
            PRIMARY KEY(id),
            INDEX url_hash (url_hash),
            INDEX name (name)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create automobiles table
        $this->addSql('CREATE TABLE automobiles (
            id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
            url_hash VARCHAR(191) NOT NULL,
            url LONGTEXT NOT NULL,
            brand_id BIGINT NOT NULL,
            name VARCHAR(191) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            press_release LONGTEXT DEFAULT NULL,
            photos LONGTEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT NULL,
            PRIMARY KEY(id),
            INDEX url_hash (url_hash),
            INDEX brand_id (brand_id),
            INDEX name (name)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create engines table
        $this->addSql('CREATE TABLE engines (
            id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
            other_id BIGINT NOT NULL COMMENT \'Engine id on autoevolution\',
            automobile_id BIGINT NOT NULL,
            name VARCHAR(191) NOT NULL,
            specs LONGTEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT NULL,
            PRIMARY KEY(id),
            INDEX other_id (other_id),
            INDEX automobile_id (automobile_id),
            INDEX name (name)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop tables in reverse order of creation
        $this->addSql('DROP TABLE IF EXISTS engines');
        $this->addSql('DROP TABLE IF EXISTS automobiles');
        $this->addSql('DROP TABLE IF EXISTS brands');
    }
}
