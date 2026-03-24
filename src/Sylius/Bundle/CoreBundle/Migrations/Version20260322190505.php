<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322190505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_repair_request (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, device_name VARCHAR(255) NOT NULL, device_brand VARCHAR(255) DEFAULT NULL, device_model VARCHAR(255) DEFAULT NULL, issue_description LONGTEXT NOT NULL, status VARCHAR(64) NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, customer_phone_number VARCHAR(64) DEFAULT NULL, diagnosis_notes LONGTEXT DEFAULT NULL, repair_notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_791670F777153098 (code), INDEX IDX_791670F79395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_repair_request ADD CONSTRAINT FK_791670F79395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT true NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_repair_request DROP FOREIGN KEY FK_791670F79395C3F3');
        $this->addSql('DROP TABLE sylius_repair_request');
        $this->addSql('ALTER TABLE sylius_banner CHANGE enabled enabled TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
