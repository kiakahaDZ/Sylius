<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create best seller cache table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_best_seller_cache (
            id INT AUTO_INCREMENT NOT NULL,
            product_id INT NOT NULL,
            period VARCHAR(50) NOT NULL,
            total_sales INT NOT NULL DEFAULT 0,
            total_quantity INT NOT NULL DEFAULT 0,
            total_revenue NUMERIC(10,2) NOT NULL DEFAULT 0,
            position INT NOT NULL DEFAULT 0,
            updated_at DATETIME NOT NULL,
            INDEX idx_period_total (period, total_sales),
            INDEX idx_product_period (product_id, period),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE INDEX idx_best_seller_product ON sylius_best_seller_cache (product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_best_seller_cache');
    }
}