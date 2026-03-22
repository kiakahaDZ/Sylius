<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240321000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create sylius_offer table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_offer (
            id INT AUTO_INCREMENT NOT NULL,
            code VARCHAR(255) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            image VARCHAR(500) DEFAULT NULL,
            link VARCHAR(500) DEFAULT NULL,
            badge VARCHAR(100) DEFAULT NULL,
            type VARCHAR(50) NOT NULL,
            position INT NOT NULL,
            start_date DATETIME DEFAULT NULL,
            end_date DATETIME DEFAULT NULL,
            target_urls JSON DEFAULT NULL,
            target_products JSON DEFAULT NULL,
            target_categories JSON DEFAULT NULL,
            target_channels JSON DEFAULT NULL,
            target_locales JSON DEFAULT NULL,
            target_user_groups JSON DEFAULT NULL,
            background_color VARCHAR(50) DEFAULT NULL,
            text_color VARCHAR(50) DEFAULT NULL,
            button_text VARCHAR(50) DEFAULT NULL,
            button_color VARCHAR(50) DEFAULT NULL,
            views INT NOT NULL,
            clicks INT NOT NULL,
            conversion_rate DOUBLE PRECISION NOT NULL,
            enabled TINYINT(1) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX idx_offer_enabled (enabled),
            INDEX idx_offer_dates (start_date, end_date),
            INDEX idx_offer_type (type),
            INDEX idx_offer_position (position),
            INDEX idx_offer_code (code),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        // PostgreSQL compatibility
        $platform = $this->connection->getDatabasePlatform()->getName();
        if ($platform === 'postgresql') {
            $this->addSql('CREATE INDEX idx_offer_enabled ON sylius_offer (enabled)');
            $this->addSql('CREATE INDEX idx_offer_dates ON sylius_offer (start_date, end_date)');
            $this->addSql('CREATE INDEX idx_offer_type ON sylius_offer (type)');
            $this->addSql('CREATE INDEX idx_offer_position ON sylius_offer (position)');
            $this->addSql('CREATE INDEX idx_offer_code ON sylius_offer (code)');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_offer');
    }
}