<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class Version20250313000002 extends AbstractPostgreSQLMigration
{
    public function getDescription(): string
    {
        return 'Create sylius_banner table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_banner (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) DEFAULT NULL,
            subtitle VARCHAR(500) DEFAULT NULL,
            link VARCHAR(500) DEFAULT NULL,
            image_path VARCHAR(500) DEFAULT NULL,
            position VARCHAR(50) DEFAULT \'hero\' NOT NULL,
            enabled BOOLEAN DEFAULT true NOT NULL,
            sort_order INT DEFAULT 0 NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_banner');
    }
}
