<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class Version20260322000101 extends AbstractPostgreSQLMigration
{
    public function getDescription(): string
    {
        return 'Creates repair request table for printer repair service module.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_repair_request (id SERIAL NOT NULL, customer_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, device_name VARCHAR(255) NOT NULL, device_brand VARCHAR(255) DEFAULT NULL, device_model VARCHAR(255) DEFAULT NULL, issue_description TEXT NOT NULL, status VARCHAR(64) NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, customer_phone_number VARCHAR(64) DEFAULT NULL, diagnosis_notes TEXT DEFAULT NULL, repair_notes TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57A3C4D977153098 ON sylius_repair_request (code)');
        $this->addSql('CREATE INDEX IDX_57A3C4D99395C3F3 ON sylius_repair_request (customer_id)');
        $this->addSql('COMMENT ON COLUMN sylius_repair_request.created_at IS "(DC2Type:datetime_immutable)"');
        $this->addSql('COMMENT ON COLUMN sylius_repair_request.updated_at IS "(DC2Type:datetime_immutable)"');
        $this->addSql('ALTER TABLE sylius_repair_request ADD CONSTRAINT FK_57A3C4D99395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_repair_request DROP CONSTRAINT FK_57A3C4D99395C3F3');
        $this->addSql('DROP TABLE sylius_repair_request');
    }
}
