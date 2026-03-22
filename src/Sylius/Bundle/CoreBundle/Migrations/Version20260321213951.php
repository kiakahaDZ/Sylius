<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class Version20260321213951 extends AbstractPostgreSQLMigration
{
    public function getDescription(): string
    {
        return 'Create Best Seller tables for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_best_seller_cache (id SERIAL NOT NULL, product_id INT NOT NULL, period VARCHAR(50) NOT NULL, total_sales INT NOT NULL, total_quantity INT NOT NULL, total_revenue NUMERIC(10, 2) NOT NULL, position INT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_period_total ON sylius_best_seller_cache (period, total_sales)');
        $this->addSql('CREATE INDEX idx_product_period ON sylius_best_seller_cache (product_id, period)');
        
        $this->addSql('CREATE TABLE sylius_best_seller_config (id SERIAL NOT NULL, display_on_homepage BOOLEAN NOT NULL, number_of_products INT NOT NULL, period VARCHAR(50) NOT NULL, "sortBy" VARCHAR(50) NOT NULL, cache_ttl INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_best_seller_cache');
        $this->addSql('DROP TABLE sylius_best_seller_config');
    }
}
