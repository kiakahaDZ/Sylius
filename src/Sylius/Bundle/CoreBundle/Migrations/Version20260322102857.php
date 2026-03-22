<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322102857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_offer (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(500) DEFAULT NULL, link VARCHAR(500) DEFAULT NULL, badge VARCHAR(100) DEFAULT NULL, type VARCHAR(50) NOT NULL, position INT NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, target_urls JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', target_products JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', target_categories JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', target_channels JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', target_locales JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', target_user_groups JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', background_color VARCHAR(50) DEFAULT NULL, text_color VARCHAR(50) DEFAULT NULL, button_text VARCHAR(50) DEFAULT NULL, button_color VARCHAR(50) DEFAULT NULL, views INT NOT NULL, clicks INT NOT NULL, conversion_rate DOUBLE PRECISION NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BD69B55F77153098 (code), INDEX idx_offer_enabled (enabled), INDEX idx_offer_dates (start_date, end_date), INDEX idx_offer_type (type), INDEX idx_offer_position (position), INDEX idx_offer_code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT true NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sylius_offer');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
