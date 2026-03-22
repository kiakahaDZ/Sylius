<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260321213950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_best_seller_cache (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, period VARCHAR(50) NOT NULL, total_sales INT NOT NULL, total_quantity INT NOT NULL, total_revenue NUMERIC(10, 2) NOT NULL, position INT NOT NULL, updated_at DATETIME NOT NULL, INDEX idx_period_total (period, total_sales), INDEX idx_product_period (product_id, period), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_best_seller_config (id INT AUTO_INCREMENT NOT NULL, display_on_homepage TINYINT(1) NOT NULL, number_of_products INT NOT NULL, period VARCHAR(50) NOT NULL, sortBy VARCHAR(50) NOT NULL, cache_ttl INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT true NOT NULL');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sylius_best_seller_cache');
        $this->addSql('DROP TABLE sylius_best_seller_config');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
