<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20260322000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates repair request table for printer repair service module.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_repair_request (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, device_name VARCHAR(255) NOT NULL, device_brand VARCHAR(255) DEFAULT NULL, device_model VARCHAR(255) DEFAULT NULL, issue_description LONGTEXT NOT NULL, status VARCHAR(64) NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, customer_phone_number VARCHAR(64) DEFAULT NULL, diagnosis_notes LONGTEXT DEFAULT NULL, repair_notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT "(DC2Type:datetime_immutable)", updated_at DATETIME DEFAULT NULL COMMENT "(DC2Type:datetime_immutable)", UNIQUE INDEX UNIQ_57A3C4D977153098 (code), INDEX IDX_57A3C4D99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_repair_request ADD CONSTRAINT FK_57A3C4D99395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_repair_request DROP FOREIGN KEY FK_57A3C4D99395C3F3');
        $this->addSql('DROP TABLE sylius_repair_request');
    }
}
